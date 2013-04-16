// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

// This file implements a small collection of filtered scanline based
// pixel operations, including scaling and shearing.

#include <emmintrin.h>
#include <pmmintrin.h>
#include <xmmintrin.h>
#include <string.h>

#include <algorithm>

#include "fastmath.h"
#include "surface.h"

// Note: none of these functions are designed to be multi-thread safe.
// They must be used consistently from the same thread.

// Note: some functions use SSE and SSE2 intrinsics.  Currently, there
// are no generic C fallback implementations in this file for CPUs that do
// not support SSE and SSE2 instructions.  (Such support is left as an
// exercise.)  In the meantime, if a machine without SSE/SSE2 capabilities
// attempts to execute this code, an exception will be raised, terminating
// the Native Client application.

typedef float float4 __attribute__ ((vector_size (4*sizeof(float))));
typedef int32_t int4 __attribute__ ((vector_size (4*sizeof(int32_t))));

static const int4 zero = { 0, 0, 0, 0 };

// Use a 2x2 filter when resampling images.
static const int k2x2Filter = 2;

// Use SSE intrinsics to expand a uint32_t into a 4 element float vector
INLINE_NO_INSTRUMENT __m128 UnpackARGB(uint32_t rgba);
inline __m128 UnpackARGB(uint32_t rgba) {
  // Expand 4 unsigned bytes to 4 32-bit zero extended integers.
  __m128i x = _mm_cvtsi32_si128(rgba);
  __m128i y = _mm_unpacklo_epi8(x, zero);
  __m128i z = _mm_unpacklo_epi16(y, zero);
  // Convert to float vector.
  __m128 f = _mm_cvtepi32_ps(z);
  return f;
}

// Use SSE intrinsics to pack a 4 element float vector down to a uint32_t.
// The result will be saturated (clamped)
INLINE_NO_INSTRUMENT uint32_t PackARGB(float4 clr);
inline uint32_t PackARGB(float4 clr) {
  // squish 4 floats into 4 8-bit unsigned chars
  // saturate (clamp) 0..1 (float) to 0..255 (uchar)
  __m128i a = _mm_cvttps_epi32(clr);
  __m128i b = _mm_packs_epi32(a, zero);
  __m128i c = _mm_packus_epi16(b, zero);
  int r = _mm_cvtsi128_si32(c);
  return r;
}

// Helper function to clip |coord| so that it lies within |min_coord| and
// |max_coord|, inclusive.
static int ClipCoordinate(int coord, int min_coord, int max_coord) {
  return std::min(std::max(min_coord, coord), max_coord);
}

Surface::Surface()
    : width_(0),
      height_(0),
      size_(0),
      border_color_(MakeARGB(0, 0, 0, 0xFF)) {
}

Surface::~Surface() {
}

void Surface::SetWidthAndHeight(int width, int height, bool force_realloc) {
  width_ = std::max(width, 1);
  height_ = std::max(height, 1);
  int newsize = width_ * height_;
  if (!force_realloc && (newsize <= size_))
    return;
  size_ = newsize;
  pixels_.reset(new uint32_t[size_]);
}

void Surface::FillScanlineNoClip(int x0, int x1, int y, uint32_t color) {
  uint32_t* pixel = PixelAddress(x0, y);
  for (int x = x0; x < x1; ++x)
     *pixel++ = color;
}

void Surface::FillScanline(int x0, int x1, int y, uint32_t color) {
  if (!is_valid())
    return;
  y = ClipCoordinate(y, 0, height_);
  x0 = ClipCoordinate(x0, 0, width_);
  x1 = ClipCoordinate(x1, 0, width_);
  if (x0 == x1)
    return;  // Nothing to do.
  if (x0 > x1) {
    std::swap(x0, x1);
  }
  FillScanlineNoClip(x0, x1, y, color);
}

void Surface::FillColumn(int x, int y0, int y1, uint32_t color) {
  if (!is_valid())
    return;
  x = ClipCoordinate(x, 0, width_);
  y0 = ClipCoordinate(y0, 0, height_);
  y1 = ClipCoordinate(y1, 0, height_);
  if (y0 == y1)
    return;
  if (y0 > y1) {
    std::swap(y0, y1);
  }
  uint32_t* pixel = PixelAddress(x, y0);
  for (int i = y0; i < y1; ++i) {
    *pixel = color;
    pixel += width_;
  }
}

void Surface::FillRect(int x0, int y0, int x1, int y1, uint32_t color) {
  if (!is_valid())
    return;
  x0 = ClipCoordinate(x0, 0, width_);
  x1 = ClipCoordinate(x1, 0, width_);
  if (x0 == x1)
    return;  // Nothing to do.
  if (x0 > x1) {
    std::swap(x0, x1);
  }
  y0 = ClipCoordinate(y0, 0, height_);
  y1 = ClipCoordinate(y1, 0, height_);
  if (y0 == y1)
    return;
  if (y0 > y1) {
    std::swap(x0, x1);
  }
  for (int y = y0; y < y1; ++y)
    FillScanlineNoClip(x0, x1, y, color);
}

void Surface::Clear(uint32_t color) {
  FillRect(0, 0, width_, height_, color);
}

void Surface::CopyScanline(int dst_x, int dst_y,
                           int src_x0, int src_x1, int src_y,
                           const Surface *src) {
  if (!is_valid() || !src->is_valid())
    return;
  dst_x = ClipCoordinate(dst_x, 0, width_);
  dst_y = ClipCoordinate(dst_y, 0, height_);
  src_x0 = ClipCoordinate(src_x0, 0, src->width());
  src_x1 = ClipCoordinate(src_x1, 0, src->width());
  if (src_x0 > src_x1) {
    std::swap(src_x0, src_x1);
  }
  src_y = ClipCoordinate(src_y, 0, src->height());

  if ((dst_x + (src_x1 - src_x0)) > width_)
    src_x1 = src_x0 + (width_ - dst_x);

  uint32_t* psrc = src->PixelAddress(src_x0, src_y);
  uint32_t* pdst = PixelAddress(dst_x, dst_y);
  memcpy(pdst, psrc, (src_x1 - src_x0) * sizeof(uint32_t));
}

void Surface::CopyScanlineNoClip(int dst_x, int dst_y,
                                 int src_x0, int src_x1, int src_y,
                                 const Surface *src) {
  uint32_t* psrc = src->PixelAddress(src_x0, src_y);
  uint32_t* pdst = PixelAddress(dst_x, dst_y);
  memcpy(pdst, psrc, (src_x1 - src_x0) * sizeof(uint32_t));
}

void Surface::Copy(int dst_x, int dst_y, const Surface *src) {
  if (!is_valid() || !src->is_valid())
    return;
  int src_x0 = 0;
  int src_x1 = src->width();
  for (int j = 0; j < src->height(); j++) {
    CopyScanline(dst_x, dst_y + j, src_x0, src_x1, j, src);
  }
}

void Surface::ScaledCopyScanline(float dst_x0, float dst_x1, int dst_y,
                                 float src_x0, float src_x1, int src_y,
                                 const Surface *src, int filter) {
  if (!is_valid() || !src->is_valid())
    return;
  filter = std::max(1, filter);
  if (dst_y < 0) dst_y = 0.0f;
  if (dst_y >= height_) dst_y = static_cast<float>(height_ - 1);
  if ((src_y < 0) || (src_y >= src->height())) {
    FillScanline(static_cast<int>(dst_x0), static_cast<int>(dst_x1),
        dst_y, src->border_color());
    return;
  }
  float delta_sx = src_x1 - src_x0;
  float delta_dx = dst_x1 - dst_x0;
  float scale_factor =  delta_dx / delta_sx;
  float oofilter = 1.0f / filter;
  float4 vc = {0.0f, 0.0f, 0.0f, 0.0f};
  const float4 vone = {1.0f, 1.0f, 1.0f, 1.0f};
  const float4 vzero = {0.0f, 0.0f, 0.0f, 0.0f};
  float4 vert_border_color;
  float v = 0.0f;
  int lastiu = 0;
  float4 vdivby = {0.0f, 0.0f, 0.0f, 0.0f};
  int leftmostu = 0;
  int rightmostu = width();
  int leftmosts = 0;
  int rightmosts = src->width();
  uint32_t *psrc = src->PixelAddress(0, src_y);
  uint32_t *pdst = PixelAddress(0, dst_y);
  vert_border_color = UnpackARGB(src->border_color());
  while (v < delta_sx) {
    float u = dst_x0 + v * scale_factor;
    int iu = static_cast<int>(u);
    if (iu != lastiu) {
      if ((lastiu >= leftmostu) && (lastiu < rightmostu)) {
        float4 voodivby = _mm_rcp_ss(vdivby);
        float4 voodivbys = _mm_shuffle_ps(voodivby, voodivby, 0);
        float4 vnc = _mm_mul_ps(vc, voodivbys);
        uint32_t c = PackARGB(vnc);
        pdst[lastiu] = c;
      }
      vc = vzero;
      vdivby = vzero;
      lastiu = iu;
    }
    float4 vsc;
    int s = static_cast<int>(src_x0 + v);
    if ((s >= leftmosts) && (s < rightmosts)) {
      uint32_t sc = psrc[s];
      vsc = UnpackARGB(sc);
    } else {
      vsc = vert_border_color;
    }
    vc += vsc;
    vdivby = _mm_add_ss(vdivby, vone);
    v += oofilter;
  }
  // put remaining bit down
  if ((lastiu >= leftmostu) && (lastiu < rightmostu)) {
    float4 voodivby = _mm_rcp_ss(vdivby);
    float4 voodivbys = _mm_shuffle_ps(voodivby, voodivby, 0);
    float4 vnc = _mm_mul_ps(vc, voodivbys);
    uint32_t c = PackARGB(vnc);
    pdst[lastiu] = c;
  }
}

void Surface::ScaledCopyColumn(int dst_x, float dst_y0, float dst_y1,
                               int src_x, float src_y0, float src_y1,
                               const Surface *src, int filter) {
  if (!is_valid() || !src->is_valid())
    return;
  filter = std::max(1, filter);
  if (dst_x < 0) dst_x = 0.0f;
  if (dst_x >= height_) dst_x = static_cast<float>(width_ - 1);
  if ((src_x < 0) || (src_x >= src->width())) {
    FillColumn(dst_x, static_cast<int>(dst_y0), static_cast<int>(dst_y1),
               src->border_color());
    return;
  }
  float delta_src_y = src_y1 - src_y0;
  float delta_dst_y = dst_y1 - dst_y0;
  float scale_factor =  delta_dst_y / delta_src_y;
  float oofilter = 1.0f / filter;
  float4 vc = {0.0f, 0.0f, 0.0f, 0.0f};
  const float4 vone = {1.0f, 1.0f, 1.0f, 1.0f};
  const float4 vzero = {0.0f, 0.0f, 0.0f, 0.0f};
  float4 vert_border_color;
  float v = 0.0f;
  int lastiu = 0;
  float4 vdivby = {0.0f, 0.0f, 0.0f, 0.0f};
  int topmostu = 0;
  int bottommostu = height();
  int topmosts = 0;
  int bottommosts = src->height();
  uint32_t *psrc = src->PixelAddress(src_x, 0);
  uint32_t *pdst = PixelAddress(dst_x, 0);
  vert_border_color = UnpackARGB(src->border_color());
  while (v < delta_src_y) {
    float u = dst_y0 + v * scale_factor;
    int iu = static_cast<int>(u);
    if (iu != lastiu) {
      if ((lastiu >= topmostu) && (lastiu < bottommostu)) {
        float4 voodivby = _mm_rcp_ss(vdivby);
        float4 voodivbys = _mm_shuffle_ps(voodivby, voodivby, 0);
        float4 vnc = _mm_mul_ps(vc, voodivbys);
        uint32_t c = PackARGB(vnc);
        pdst[lastiu * width()] = c;
      }
      vc = vzero;
      vdivby = vzero;
      lastiu = iu;
    }
    float4 vsc;
    int s = static_cast<int>(src_y0 + v);
    if ((s >= topmosts) && (s < bottommosts)) {
      uint32_t sc = psrc[s * src->width()];
      vsc = UnpackARGB(sc);
    } else {
      vsc = vert_border_color;
    }
    vc += vsc;
    vdivby = _mm_add_ss(vdivby, vone);
    v += oofilter;
  }
  // Put remaining bit down.
  if ((lastiu >= topmostu) && (lastiu < bottommostu)) {
    float4 voodivby = _mm_rcp_ss(vdivby);
    float4 voodivbys = _mm_shuffle_ps(voodivby, voodivby, 0);
    float4 vnc = _mm_mul_ps(vc, voodivbys);
    uint32_t c = PackARGB(vnc);
    pdst[lastiu * width()] = c;
  }
}


void Surface::RescaleFrom(const Surface *src,
                          float src_x,
                          float src_y,
                          Surface *tmp) {
  if (!is_valid() || !src->is_valid())
    return;
  tmp->SetWidthAndHeight(src->width() * src_x, src->height(), false);
  for (int y = 0; y < src->height(); ++y) {
    tmp->ScaledCopyScanline(0.0f, src->width() * src_x, y,
                            0.0f, static_cast<float>(src->width()), y,
                            src, k2x2Filter);
  }
  SetWidthAndHeight(src->width() * src_x, src->height() * src_y, false);
  for (int x = 0; x < tmp->width(); ++x) {
    ScaledCopyColumn(x, 0.0f, tmp->height() * src_y,
                     x, 0.0f, static_cast<float>(tmp->height()),
                     tmp, k2x2Filter);
  }
}

void Surface::ShearedCopyScanline(float dst_tx0, float dst_bx0, int dst_y,
                                  int src_x0, int src_x1, int src_y,
                                  const Surface *src) {
  if (!is_valid() || !src->is_valid())
    return;
  float dst_tx0c = floor(dst_tx0 + 1.0f);
  float dst_bx0c = floor(dst_bx0 + 1.0f);
  uint32_t src_border_color = src->border_color();

  if ((dst_y < 0) || (dst_y >= height())) {
    return;
  }
  if (static_cast<int>(dst_tx0) > width()) {
    return;
  }
  if ((static_cast<int>(dst_tx0) + (src_x1 - src_x0)) < 0) {
    return;
  }
  if ((src_x0 >= src->width()) || (src_x1 < 0) ||
      (src_y < 0) || (src_y >= src->height())) {
    // TODO(nfullagar): implement anti-aliased fill and return (endpoints need
    // to be anti-aliased).  In the meantime, fall through to simple BLT code
    // below, which can still handle this situation -- it will just be a little
    // slower.
  }
  float f0;
  float f1;
  float f2;
  if (static_cast<int>(dst_tx0c) == static_cast<int>(dst_bx0c)) {
    // Both leftmost corners are in the same dest pixel
    // and the filter only applies to two source pixels
    f2 = 0.5f * (dst_tx0 - dst_bx0) + (dst_tx0c - dst_tx0);
    f1 = 1.0f - f2;
    f0 = 0.0f;
  } else {
    // Each leftmost corner falls into different dest pixels
    // and the filter applies to three source pixels
    if (dst_bx0 < dst_tx0) {
      f2 = 0.5f * (dst_bx0c - dst_bx0);
      f0 = 0.5f * (dst_tx0 - dst_bx0c);
    } else {
      f2 = 0.5f * (dst_tx0c - dst_tx0);
      f0 = 0.5f * (dst_bx0 - dst_tx0c);
    }
    f1 = 1.0f - (f0 + f2);
  }

  // Build temporary vectors.
  float4 vf0 = {f0, f0, f0, 1.0f};
  float4 vf1 = {f1, f1, f1, 0.0f};
  float4 vf2 = {f2, f2, f2, 0.0f};
  float4 vert_border_color = UnpackARGB(src_border_color);
  float4 vc0 = vert_border_color;
  float4 vc1 = vert_border_color;
  float4 vc2 = vert_border_color;

  int sx = src_x0;
  int dx = (dst_tx0 < dst_bx0) ?
      static_cast<int>(floor(dst_tx0)) :
      static_cast<int>(floor(dst_bx0));
  uint32_t *srcp = src->PixelAddress(0, src_y);
  uint32_t *dstp = PixelAddress(0, dst_y);

  // Skip pixels off the left side.  Jump to left edge, but offset by -2 so we
  // can initialize the shearing operation.
  if (dx < 0) {
    sx = sx - dx - 2;
    dx = -2;

    // Do two pixels worth to initialize the shearing operation.
    for (int i = 0; i < 2; ++i) {
      // Rotate color registers
      vc0 = vc1;
      vc1 = vc2;
      if ((sx >= 0) && (sx < src->width())) {
        uint32_t sc = srcp[sx];
        vc2 = UnpackARGB(sc);
      } else {
        vc2 = vert_border_color;
      }
      ++dx;
      ++sx;
    }
  }

  // Compute the stopping point, clip against right edge if needed.
  int end_dx = dx + (src_x1 - sx);
  if (end_dx > width()) {
    end_dx = width();
  }

  while (dx < end_dx) {
    // Rotate color registers.
    vc0 = vc1;
    vc1 = vc2;

    // Fetch the next color.
    if ((sx >= 0) && (sx < src->width())) {
      uint32_t sc = srcp[sx];
      vc2 = UnpackARGB(sc);
    } else {
      vc2 = vert_border_color;
    }

    // Compute filtered color value.
    __m128 vtmp0 = _mm_mul_ps(vc0, vf0);
    __m128 vtmp1 = _mm_mul_ps(vc1, vf1);
    __m128 vtmp2 = _mm_mul_ps(vc2, vf2);
    __m128 vacc0 = _mm_add_ps(vtmp0, vtmp1);
    __m128 vclr = _mm_add_ps(vacc0, vtmp2);

    // Store filtered color value.
    dstp[dx] = PackARGB(vclr);

    // Advance to next pixel.
    ++dx;
    ++sx;
  }
}

void Surface::ShearedCopyColumn(int dst_x, float dst_ly0, float dst_ry0,
                                int src_x, int src_y0, int src_y1,
                                const Surface *src) {
  if (!is_valid() || !src->is_valid())
    return;
  float dst_ly0c = floor(dst_ly0 + 1.0f);
  float dst_ry0c = floor(dst_ry0 + 1.0f);
  uint32_t src_border_color = src->border_color();

  if ((dst_x < 0) || (dst_x >= width())) {
    return;
  }
  if (static_cast<int>(dst_ly0) > height()) {
    return;
  }
  if ((static_cast<int>(dst_ly0) + (src_y1 - src_y0)) < 0) {
    return;
  }
  if ((src_y0 >= src->height()) || (src_y1 < 0) ||
      (src_x < 0) || (src_x >= src->width())) {
    // TODO(nfullagar): implement anti-aliased fill and return (endpoints need
    // to be anti-aliased).  In the meantime, fall through to simple BLT code
    // below, which can still handle this situation -- it will just be a little
    // slower.
  }
  float f0;
  float f1;
  float f2;
  if (static_cast<int>(dst_ly0c) == static_cast<int>(dst_ry0c)) {
    // Both topmost corners are in the same dest pixel
    // and the filter only applies to two source pixels.
    f2 = 0.5f * (dst_ly0 - dst_ry0) + (dst_ly0c - dst_ly0);
    f1 = 1.0f - f2;
    f0 = 0.0f;
  } else {
    // Each topmost corner falls into different dest pixels
    // and the filter applies to three source pixels.
    if (dst_ry0 < dst_ly0) {
      f2 = 0.5f * (dst_ry0c - dst_ry0);
      f0 = 0.5f * (dst_ly0 - dst_ry0c);
    } else {
      f2 = 0.5f * (dst_ly0c - dst_ly0);
      f0 = 0.5f * (dst_ry0 - dst_ly0c);
    }
    f1 = 1.0f - (f0 + f2);
  }

  // Build temporary vectors.
  float4 vf0 = {f0, f0, f0, 1.0f};
  float4 vf1 = {f1, f1, f1, 0.0f};
  float4 vf2 = {f2, f2, f2, 0.0f};
  float4 vert_border_color = UnpackARGB(src_border_color);
  float4 vc0 = vert_border_color;
  float4 vc1 = vert_border_color;
  float4 vc2 = vert_border_color;

  int src_y = src_y0;
  int dst_y = (dst_ly0 < dst_ry0) ?
      static_cast<int>(floor(dst_ly0)) :
      static_cast<int>(floor(dst_ry0));
  uint32_t *srcp = src->PixelAddress(src_x, 0);
  uint32_t *dstp = PixelAddress(dst_x, 0);

  // Skip pixels above the top.
  if (dst_y < 0) {
    // Jump to top edge, but offset by -2 so we can initialize the shear
    // operation.
    src_y = src_y - dst_y - 2;
    dst_y = -2;

    // Do two pixels initialize the shear operation.
    for (int i = 0; i < 2; ++i) {
      vc0 = vc1;
      vc1 = vc2;
      if ((src_y >= 0) && (src_y < src->height())) {
        // Within source, use read from source pixel.
        uint32_t sc = srcp[src_y * src->width()];
        vc2 = UnpackARGB(sc);
      } else {
        // Otherwise use src's border color.
        vc2 = vert_border_color;
      }
      ++dst_y;
      ++src_y;
    }
  }

  // Compute stopping point, clip to bottom edge if needed.
  int end_dst_y = dst_y + (src_y1 - src_y);
  if (end_dst_y > height()) {
    end_dst_y = height();
  }

  while (dst_y < end_dst_y) {
    vc0 = vc1;
    vc1 = vc2;
    if ((src_y >= 0) && (src_y < src->height())) {
      // Within source, so read from source pixel.
      uint32_t sc = srcp[src_y * src->width()];
      vc2 = UnpackARGB(sc);
    } else {
      // Otherwise use src's border color.
      vc2 = vert_border_color;
    }

    // Compute filtered color value.
    __m128 vtmp0 = _mm_mul_ps(vc0, vf0);
    __m128 vtmp1 = _mm_mul_ps(vc1, vf1);
    __m128 vtmp2 = _mm_mul_ps(vc2, vf2);
    __m128 vacc0 = _mm_add_ps(vtmp0, vtmp1);
    __m128 vclr = _mm_add_ps(vacc0, vtmp2);

    // Store filtered color value.
    dstp[dst_y * width()] = PackARGB(vclr);

    ++dst_y;
    ++src_y;
  }
}

void Surface::Rotate(float degrees, const Surface *src, Surface *tmp) {
  if (!is_valid() || !src->is_valid())
    return;
  float org_x = static_cast<float>(src->width()) * 0.5f;
  float org_y = static_cast<float>(src->height()) * 0.5f;
  float radians = M_PI * degrees / 180.0f;
  float tanarg = -tan(radians * 0.5f);
  float sinarg = sin(radians);

  degrees = std::max(-45.0f, std::min(45.0f, degrees));
  tmp->SetWidthAndHeight(src->width(), src->height(), false);
  tmp->Clear();

  // Rotation -45..45 degrees as a product of 3 shears.

  // 1) horizontal shear
  int iy = 0;
  for (float y = -org_y; y < org_y; y += 1.0f) {
    float ty = y;
    float by = y + 1.0f;
    float tx = -org_x + tanarg * ty;
    float bx = -org_x + tanarg * by;
    ShearedCopyScanline(tx + org_x, bx + org_x, iy, 0, src->width(), iy, src);
    iy = iy + 1;
  }

  // 2) vertical shear
  int ix = 0;
  for (float x = -org_x; x < org_x; x += 1.0f) {
    float lx = x;
    float rx = x + 1.0f;
    float ly = -org_y + sinarg * lx;
    float ry = -org_y + sinarg * rx;
    tmp->ShearedCopyColumn(ix, ly + org_y, ry + org_y, ix, 0,
        height(), this);
    ix = ix + 1;
  }

  // 3) horizontal shear
  iy = 0;
  for (float y = -org_y; y < org_y; y += 1.0f) {
    float ty = y;
    float by = y + 1.0f;
    float tx = -org_x + tanarg * ty;
    float bx = -org_x + tanarg * by;
    ShearedCopyScanline(tx + org_x, bx + org_x, iy, 0, tmp->width(), iy, tmp);
    iy = iy + 1;
  }
}
