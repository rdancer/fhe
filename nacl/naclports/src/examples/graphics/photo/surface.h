// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#ifndef PHOTO_SURFACE_H_
#define PHOTO_SURFACE_H_

#include <errno.h>
#include <math.h>
#include <pthread.h>
#include <stdint.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

#include <boost/scoped_array.hpp>

#define INLINE_NO_INSTRUMENT \
    __attribute__((no_instrument_function, always_inline))


#define MAX_RECIPROCAL_TABLE 256

// build a packed color
INLINE_NO_INSTRUMENT
    uint32_t MakeARGB(uint8_t r, uint8_t g, uint8_t b, uint8_t a);
inline uint32_t MakeARGB(uint8_t r, uint8_t g, uint8_t b, uint8_t a) {
  return (((a) << 24) | ((r) << 16) | ((g) << 8) | (b));
}

// extract R, G, B, A from packed color
INLINE_NO_INSTRUMENT int ExtractR(uint32_t c);
inline int ExtractR(uint32_t c) {
  return (c >> 16) & 0xFF;
}

INLINE_NO_INSTRUMENT int ExtractG(uint32_t c);
inline int ExtractG(uint32_t c) {
  return (c >> 8) & 0xFF;
}

INLINE_NO_INSTRUMENT int ExtractB(uint32_t c);
inline int ExtractB(uint32_t c) {
  return c & 0xFF;
}

INLINE_NO_INSTRUMENT int ExtractA(uint32_t c);
inline int ExtractA(uint32_t c) {
  return (c >> 24) & 0xFF;
}

class Surface {
 public:
  // Ctor creates an empty, invalid surface.  Use SetWidthAndHeight()
  // to fully initialize the Surface.
  Surface();
  ~Surface();

  // Change the size of the surface to (width, height).  The minimum dimension
  // is clamped to (1, 1).  If |force_realloc| flag is set, free the old pixel
  // array and allocate a new one.  Otherwise, use the existing allocated memory
  // if it is large enough.  In all cases, if the existing pixel buffer is too
  // small, it is replace with a new larger one.  The new pixel buffer is left
  // as raw memory: if a new one is allocated, the old one is not copied, nor
  // is the new buffer cleared.  Use Copy() or Clear() to re-fill the new
  // (ore resized) buffer.
  void SetWidthAndHeight(int width, int height, bool force_realloc);

  // Sets a pixel at a linear offset in memory.  Does not check bounds or if
  // the Surface is valid.
  void PutPixelAt(int i, uint32_t color) {
    pixels_[i] = color;
  }

  // Get the pixel value at a linear offset in memory.  Does not check bounds
  // of if the Surface is valid.
  uint32_t GetPixelAt(int i) const {
    return pixels_[i];
  }

  // Set the pixel at 2D address (|x|, |y|) to |color| without any bounds
  // checking on the values of |x| and |y|.
  void PutPixelNoClip(int x, int y, uint32_t color) {
    PutPixelAt(y * width_ + x, color);
  }

  // Retrieve the pixel value at 2D address (|x|, |y|) without any bounds
  // checking on the values of |x| and |y|.
  uint32_t GetPixelNoClip(int x, int y) const {
    return GetPixelAt(y * width_ + x);
  }

  // Set the pixel at 2D address (|x|, |y|) to |color| with bounds checking
  // on the values of |x| and |y|.  If either |x| or |y| are out of bounds,
  // this method does nothing.
  void PutPixel(int x, int y, uint32_t color) {
    if (is_valid() && (x >= 0) && (x < width_) && (y >= 0) && (y < height_)) {
      PutPixelAt(y * width_ + x, color);
    }
  }

  // Retrieve the pixel value at 2D address (|x|, |y|) with bounds checking
  // on the values of |x| and |y|.  If either |x| or |y| are out of bounds,
  // return the border color.
  uint32_t GetPixel(int x, int y) const {
    if (!is_valid() || (x < 0) || (x >= width_) || (y < 0) || (y >= height_)) {
      return border_color_;
    }
    return GetPixelAt(y * width_ + x);
  }

  // Fills the scanline at |y| with |color|.  Start in the scanline at pixel
  // coordinate |x0| and fill up to but not including pixel |x1|.  Does not
  // check bounds or x-coordinates.  If the Surface is not valid, do nothing.
  void FillScanlineNoClip(int x0, int x1, int y, uint32_t color);

  // Fills the scanline at |y| with |color|.  Start in the scanline at pixel
  // coordinate |x0| and fill up to but not including pixel |x1|.  Clamps |y|
  // to be in range [0 .. |height_|] and clamps both x-coordinates to be in
  // range [0 .. |width_|].  Swaps |x0| and |x1| if necessary so that |x0| <=
  // |x1|.
  void FillScanline(int x0, int x1, int y, uint32_t color);

  // Fill the rectangular region with origin (x0, y0) representing the upper-
  // left of the rectangle and opposite (lower-right) corner at (x1, y1).
  // Fills up to, but not including (x1, y1).  Clips all coordinates to lie
  // within the Surface.
  void FillRect(int x0, int y0, int x1, int y1, uint32_t color);

  // Special case of FillRect() where the rectangle is one pixel wide.  This
  // call is equivalent to FillRect(x, y0, x + 1, y1, color).  Clamps all
  // coordinates to lie within the Surface.
  void FillColumn(int x, int y0, int y1, uint32_t color);

  // Fill the entire surface with a solid color
  void Clear(uint32_t color);
  void Clear() {
    Clear(border_color_);
  }

  // Copy the horizontal scanline from |src| to this Surface.  The scanline is
  // located at pixel coordinates (|src_x0|, |src_y|) and runs to location
  // (|src_x1|, |src_y|).  The scanline is copied to location (|dst_x|,
  // |dst_y|).  No coordinate clipping is performed.
  void CopyScanlineNoClip(int dst_x, int dst_y,
                          int src_x0, int src_x1, int src_y,
                          const Surface *src);

  // Copy the horizontal scanline from |src| to this Surface.  The scanline is
  // located at pixel coordinates (|src_x0|, |src_y|) and runs to location
  // (|src_x1|, |src_y|).  The scanline is copied to location (|dst_x|,
  // |dst_y|). All coordinates are clipped to the respective Surfaces before
  // copying.
  void CopyScanline(int dst_x, int dst_y,
                    int src_x0, int src_x1, int src_y,
                    const Surface *src);

  // Copy all of |src| into this Surface, placing the copied image's upper-left
  // corner at pixel coordinates (|dst_x|, |dst_y|).  Clips |src| to fit, clips
  // all coordinates to lie within the Surface.
  void Copy(int dst_x, int dst_y, const Surface *src);

  // Copy the horizontal scanline from |src| to this Surface, applying a scale
  // such that all of the source pixels fit into the destination pixels.
  // |filter| indicates how wide of a box filter to use (typically 2 pixels).
  // The scanline is located at pixel coordinates (|src_x0|, |src_y|) and runs
  // to location (|src_x1|, |src_y|).  The scanline is copied (and scaled) to
  // location (|dst_x|, |dst_y|).  All coordinates are clipped to fit in their
  // respective Surfaces.
  void ScaledCopyScanline(float dst_x0, float dst_x1, int dst_y,
                          float src_x0, float src_x1, int src_y,
                          const Surface *src, int filter);

  // Copy the vertical column from |src| to this Surface, applying a scale
  // such that all of the source pixels fit into the destination pixels.
  // |filter| indicates how wide of a box filter to use (typically 2 pixels).
  // The scanline is located at pixel coordinates (|src_x|, |src_y0|) and runs
  // to location (|src_x|, |src_y1|).  The scanline is copied (and scaled) to
  // location (|dst_x|, |dst_y0|).  All coordinates are clipped to fit in their
  // respective Surfaces.
  void ScaledCopyColumn(int dst_x, float dst_y0, float dst_y1,
                        int src_x, float src_y0, float src_y1,
                        const Surface *src, int filter);

  // Copy the horizontal scanline from |src| to this Surface, applying a
  // shear transform to the source pixels.  The shearing angle is determined
  // by the difference between |dst_tx0| and |dst_bx0|.  Only limited angle
  // shearing is supported: up to three source pixels can contribute to a
  // destination pixel; applies shearing only, no scaling and no subpixel
  // source offset.  Does nothing if any of the pixel coordinates fall outside
  // of their respective Surfaces.
  void ShearedCopyScanline(float dst_tx0, float dst_bx0, int dst_y,
                           int src_x0, int src_x1, int src_y,
                           const Surface *src);

  // Copy the vertical colum from |src| to this Surface, applying a
  // shear transform to the source pixels.  The shearing angle is determined
  // by the difference between |dst_ly0| and |dst_ry0|.  Only limited angle
  // shearing is supported: up to three source pixels can contribute to a
  // destination pixel; applies shearing only, no scaling and no subpixel
  // source offset.  Does nothing if any of the pixel coordinates fall outside
  // of their respective Surfaces.
  void ShearedCopyColumn(int dst_x, float dst_ly0, float dst_ry0,
                         int src_x, int src_y0, int src_y1,
                         const Surface *src);

  // Copy the |src| Surface into this one, applying a rotation by |degrees|.
  // |degrees| must be in range [-45 .. 45].  The rotation is done via 3
  // shearing operations.  This is slower than single pass, but easier to
  // implement with good filtering.  |tmp| is provided as a scratch area.
  // TODO(nfullagar): at some point, implement cropping on the result, such
  // that the largest axis-aligned rectangle which fits within the rotated
  // rectangle.
  void Rotate(float degrees, const Surface *src, Surface *tmp);

  // Copy all of |src| into this Surface, placing the copied image's upper-left
  // corner at pixel coordinates (|dst_x|, |dst_y|).  Scales |src| such that it
  // fits into this Surface's dimensions.
  void RescaleFrom(const Surface *src, float sx, float sy, Surface *tmp);

  // A Surface is valid if it has a dimension of at least (1, 1) and there is
  // a pixel store allocated.
  bool is_valid() const {
    return size_ > 0 && pixels_.get() != NULL;
  }

  void set_border_color(uint32_t color) {
    border_color_ = color;
  }
  uint32_t border_color() const {
    return border_color_;
  }

  int width() const {
    return width_;
  }
  int height() const {
    return height_;
  }
  const uint32_t* pixels() const {
    return pixels_.get();
  }

 private:
  // Used internally to get the address of a pixel at location (|x|, |y|).
  // Does not check bounds.
  inline uint32_t* PixelAddress(int x, int y) const {
    return &pixels_[x + y * width_];
  }

  int width_;
  int height_;
  int size_;
  uint32_t border_color_;
  boost::scoped_array<uint32_t> pixels_;
};

#endif  // PHOTO_SURFACE_H_
