// Copyright 2010 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include <assert.h>
#include <math.h>
#include <stdlib.h>
#include <string.h>

#include <algorithm>
#include <string>

#include <nacl/nacl_npapi.h>
#include <nacl/npapi_extensions.h>
#include <nacl/npupp.h>

#define JS_LOG(msg) \
    const size_t line_number = __LINE__; \
    size_t len = floor(line_number) + 1; \
    len = std::string(msg).size() + strlen(__FILE__) + len + 5; \
    char buffer[len]; \
    memset(buffer, 0, len); \
    snprintf(buffer, len, "%s:%i - ", __FILE__, line_number); \
    strncat(buffer, std::string(msg).c_str(), len - strlen(buffer)); \
    Log(npp_, buffer);

namespace {
// Log given message to javascript console.
bool Log(NPP npp, const char* msg, ...) {
  bool rv = false;
  NPObject* window = NULL;
  if (NPERR_NO_ERROR == NPN_GetValue(npp, NPNVWindowNPObject, &window)) {
    const char buffer[] = "top.console";
    NPString console_stript = { 0 };
    console_stript.UTF8Length = strlen(buffer);
    console_stript.UTF8Characters = buffer;
    NPVariant console;
    if (NPN_Evaluate(npp, window, &console_stript, &console)) {
      if (NPVARIANT_IS_OBJECT(console)) {
        // Convert the message to NPString;
        NPVariant text;
        STRINGN_TO_NPVARIANT(msg, static_cast<uint32_t>(strlen(msg)),
                             text);
        NPVariant result;
        if (NPN_Invoke(npp, NPVARIANT_TO_OBJECT(console),
                       NPN_GetStringIdentifier("log"), &text, 1, &result)) {
          NPN_ReleaseVariantValue(&result);
          rv = true;
        }
      }
      NPN_ReleaseVariantValue(&console);
    }
  }
  return rv;
}

// seed for rand_r() - we only call rand_r from main thread.
static unsigned int gSeed = 0xC0DE533D;

// random number helper
// binary rand() returns 0 or 1
inline unsigned char brand() {
  return static_cast<unsigned char>(rand_r(&gSeed) & 1);
}

inline uint32_t MakeRGBA(uint32_t r, uint32_t g, uint32_t b, uint32_t a) {
  return (((a) << 24) | ((r) << 16) | ((g) << 8) | (b));
}

void FlushCallback(NPP instance, NPDeviceContext* context,
                   NPError err, void* user_data) {
}

void* life(void* data);

// Life class holds information and functionality needed to render
// life into an Pepper 2D surface.
class Life : public NPObject {
 public:
  Life(NPP npp);
  ~Life();
  NPError SetWindow(NPWindow* window);
  void Update();
  void Plot(int x, int y);
  void Stir();
  void Draw();
  void UpdateCells();
  void Swap();
  void HandleEvent(NPPepperEvent* event);

 private:
  void CreateContext();
  void DestroyContext();
  bool IsContextValid() {
    return context2d_.region != NULL;
  }
  int width() const {
    return width_;
  }
  int height() const {
    return height_;
  }
  void* pixels() {
    return context2d_.region;
  }

  NPP       npp_;
  NPExtensions* extensions_;
  int width_, height_;
  NPDevice* device2d_;  // The PINPAPI 2D device.
  NPDeviceContext2D context2d_;  // The PINPAPI 2D drawing context.
  bool scribble_;
  bool quit_;
  char *cell_in_;
  char *cell_out_;
};

Life::Life(NPP npp) : npp_(npp), extensions_(NULL), width_(0), height_(0),
  device2d_(NULL), cell_in_(NULL), cell_out_(NULL) {
  memset(&context2d_, 0, sizeof(context2d_));
  NPN_GetValue(npp_, NPNVPepperExtensions, &extensions_);
  device2d_ = extensions_->acquireDevice(npp_, NPPepper2DDevice);
  assert(extensions_);
}

Life::~Life() {
  delete[] cell_in_;
  delete[] cell_out_;
  DestroyContext();
}

void Life::CreateContext() {
  if (IsContextValid())
    return;
  device2d_ = extensions_->acquireDevice(npp_, NPPepper2DDevice);
  assert(device2d_);
  NPDeviceContext2DConfig config;
  NPError init_err = device2d_->initializeContext(npp_, &config, &context2d_);
  assert(NPERR_NO_ERROR == init_err);
}

void Life::HandleEvent(NPPepperEvent* event) {
  bool plot = false;
  if (event->type == NPEventType_MouseDown) {
    scribble_ = true;
    plot = true;
  }
  if (event->type ==  NPEventType_MouseUp) {
    JS_LOG("MouseUp event");
    scribble_ = false;
  }
  if (event->type == NPEventType_MouseMove) {
    plot = scribble_;
  }
  if (plot) {
    // place a blob of life
    Plot(event->u.mouse.x - 1, event->u.mouse.y - 1);
    Plot(event->u.mouse.x + 0, event->u.mouse.y - 1);
    Plot(event->u.mouse.x + 1, event->u.mouse.y - 1);
    Plot(event->u.mouse.x - 1, event->u.mouse.y + 0);
    Plot(event->u.mouse.x + 0, event->u.mouse.y + 0);
    Plot(event->u.mouse.x + 1, event->u.mouse.y + 0);
    Plot(event->u.mouse.x - 1, event->u.mouse.y + 1);
    Plot(event->u.mouse.x + 0, event->u.mouse.y + 1);
    Plot(event->u.mouse.x + 1, event->u.mouse.y + 1);
  }
}

void Life::DestroyContext() {
  if (!IsContextValid())
    return;
  device2d_->destroyContext(npp_, &context2d_);
}

void Life::Plot(int x, int y) {
  if (x < 0) return;
  if (x >= width()) return;
  if (y < 0) return;
  if (y >= height()) return;
  *(cell_in_ + x + y * width()) = 1;
}

NPError Life::SetWindow(NPWindow* window) {
  if (!window)
    return NPERR_NO_ERROR;
  width_ = window->width;
  height_ = window->height;
  if (!IsContextValid())
    CreateContext();

  const size_t size = width() * height();
  delete[] cell_in_;
  delete[] cell_out_;
  cell_in_ = new char[size];
  cell_out_ = new char[size];
  std::fill(cell_in_, cell_in_ + size, 0);
  std::fill(cell_out_, cell_out_ + size, 0);

  NPDeviceFlushContextCallbackPtr callback =
      reinterpret_cast<NPDeviceFlushContextCallbackPtr>(&FlushCallback);
  device2d_->flushContext(npp_, &context2d_, callback, NULL);
  return NPERR_NO_ERROR;
}

void Life::Update() {
  Stir();
  UpdateCells();
  Swap();
  NPDeviceFlushContextCallbackPtr callback =
      reinterpret_cast<NPDeviceFlushContextCallbackPtr>(&FlushCallback);
  device2d_->flushContext(npp_, &context2d_, callback, NULL);
}

void Life::Stir() {
  const int height = this->height();
  const int width = this->width();
  for (int i = 0; i < width; ++i) {
    cell_in_[i] = brand();
    cell_in_[i + (height - 1) * width] = brand();
  }
  for (int i = 0; i < height; ++i) {
    cell_in_[i * width] = brand();
    cell_in_[i * width + (width - 2)] = brand();
  }
}

void Life::UpdateCells() {
  // map neighbor count to color
  static unsigned int colors[18] = {
      MakeRGBA(0x00, 0x00, 0x00, 0xff),
      MakeRGBA(0x00, 0x40, 0x00, 0xff),
      MakeRGBA(0x00, 0x60, 0x00, 0xff),
      MakeRGBA(0x00, 0x80, 0x00, 0xff),
      MakeRGBA(0x00, 0xA0, 0x00, 0xff),
      MakeRGBA(0x00, 0xC0, 0x00, 0xff),
      MakeRGBA(0x00, 0xE0, 0x00, 0xff),
      MakeRGBA(0x00, 0x00, 0x00, 0xff),
      MakeRGBA(0x00, 0x40, 0x00, 0xff),
      MakeRGBA(0x00, 0x60, 0x00, 0xff),
      MakeRGBA(0x00, 0x80, 0x00, 0xff),
      MakeRGBA(0x00, 0xA0, 0x00, 0xff),
      MakeRGBA(0x00, 0xC0, 0x00, 0xff),
      MakeRGBA(0x00, 0xE0, 0x00, 0xff),
      MakeRGBA(0x00, 0xFF, 0x00, 0xff),
      MakeRGBA(0x00, 0xFF, 0x00, 0xff),
      MakeRGBA(0x00, 0xFF, 0x00, 0xff),
      MakeRGBA(0x00, 0xFF, 0x00, 0xff),
  };
  // map neighbor count to alive/dead
  static char replace[18] = {
      0, 0, 0, 1, 0, 0, 0, 0,  // row for center cell dead
      0, 0, 1, 1, 0, 0, 0, 0,  // row for center cell alive
  };
  const int height = this->height();
  const int width = this->width();
  // do neighbor sumation; apply rules, output pixel color
  for (int y = 1; y < (height - 1); ++y) {
    char *src0 = cell_in_ + (y - 1) * width;
    char *src1 = cell_in_ + (y) * width;
    char *src2 = cell_in_ + (y + 1) * width;
    int count;
    unsigned int color;
    char *dst = cell_out_ + (y) * width;
    uint32_t *pixels = static_cast<uint32_t*>(this->pixels()) + y * width;
    for (int x = 1; x < (width - 1); ++x) {
      // build sum, weight center by 8x
      count = src0[-1] +     src0[0] +        src0[1] +
              src1[-1] +     src1[0] * 8 +    src1[1] +
              src2[-1] +     src2[0] +        src2[1];
      color = colors[count];
      *pixels++ = color;
      *dst++ = replace[count];
      ++src0, ++src1, ++src2;
    }
  }
}

void Life::Swap() {
  char* tmp = cell_in_;
  cell_in_ = cell_out_;
  cell_out_ = tmp;
}

extern "C" {

// The following functions implement functions to be used with npruntime.
static NPObject* AllocateLife(NPP npp, NPClass* npclass) {
  Life* rv = new Life(npp);
  return rv;
}

static void Deallocate(NPObject* obj) {
  Life* const life = static_cast<Life*>(obj);
  delete life;
}

// These are for npruntime.
static void Invalidate(NPObject*) {
}

static bool HasMethod(NPObject*, NPIdentifier name) {
  bool rv = false;
  NPUTF8* method_name = NPN_UTF8FromIdentifier(name);
  if (0 == memcmp(method_name, "update", sizeof("update"))) {
    rv = true;
  }
  NPN_MemFree(method_name);
  return rv;
}

static bool Invoke(NPObject *obj, NPIdentifier name, const NPVariant *args,
                   uint32_t argc, NPVariant *result) {
  bool rv = false;
  NPUTF8* method_name = NPN_UTF8FromIdentifier(name);
  if (0 == memcmp(method_name, "update", sizeof("update"))) {
    rv = true;
    Life* const life = static_cast<Life*>(obj);
    life->Update();
  }
  NPN_MemFree(method_name);
  return rv;
}

static bool InvokeDefault(NPObject *npobj, const NPVariant *args,
                          uint32_t argCount, NPVariant *result) {
  return false;
}

static bool HasProperty(NPObject *npobj, NPIdentifier name) {
  return false;
}

static bool GetProperty(NPObject *npobj, NPIdentifier name, NPVariant *result) {
  return false;
}

static bool SetProperty(NPObject *npobj, NPIdentifier name,
                        const NPVariant *value) {
  return false;
}

static bool RemoveProperty(NPObject *npobj, NPIdentifier name) {
  return false;
}

NPClass np_class = {
  NP_CLASS_STRUCT_VERSION,
  AllocateLife,
  Deallocate,
  Invalidate,
  HasMethod,
  Invoke,
  InvokeDefault,
  HasProperty,
  GetProperty,
  SetProperty,
  RemoveProperty
};

// These functions are required by both the develop and publish versions,
// they are called when a module instance is first loaded, and when the module
// instance is finally deleted.  They must use C-style linkage.
NPError NPP_Destroy(NPP instance, NPSavedData** save) {
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }

  Life* life = static_cast<Life*>(instance->pdata);
  if (life != NULL) {
    NPN_ReleaseObject(life);
  }
  return NPERR_NO_ERROR;
}

// NPP_GetScriptableInstance retruns the NPObject pointer that corresponds to
// NPPVpluginScriptableNPObject queried by NPP_GetValue() from the browser.
NPObject* NPP_GetScriptableInstance(NPP instance) {
  if (instance == NULL) {
    return NULL;
  }
  Life* life = static_cast<Life*>(instance->pdata);
  return life;
}

NPError NPP_GetValue(NPP instance, NPPVariable variable, void *value) {
  if (NPPVpluginScriptableNPObject == variable) {
    NPObject* scriptable_object = NPP_GetScriptableInstance(instance);
    if (scriptable_object == NULL)
      return NPERR_INVALID_INSTANCE_ERROR;
    *reinterpret_cast<NPObject**>(value) = scriptable_object;
    return NPERR_NO_ERROR;
  }
  return NPERR_INVALID_PARAM;
}

int16_t NPP_HandleEvent(NPP instance, void* event) {
  Life* life = static_cast<Life*>(instance->pdata);
  if (NULL != life) {
    NPPepperEvent* npevent = static_cast<NPPepperEvent*>(event);
    life->HandleEvent(npevent);
  }
  return 0;
}

NPError NPP_New(NPMIMEType mime_type,
                NPP instance,
                uint16_t mode,
                int16_t argc,
                char* argn[],
                char* argv[],
                NPSavedData* saved) {
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }

  Life* const life = static_cast<Life*>(NPN_CreateObject(instance, &np_class));
  instance->pdata = life;
  return NPERR_NO_ERROR;
}

NPError NPP_SetWindow(NPP instance, NPWindow* window) {
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }
  if (window == NULL) {
    return NPERR_GENERIC_ERROR;
  }
  Life* life = static_cast<Life*>(instance->pdata);
  if (life != NULL) {
    return life->SetWindow(window);
  }
  return NPERR_NO_ERROR;
}

NPError NP_GetEntryPoints(NPPluginFuncs* plugin_funcs) {
  extern NPError InitializePluginFunctions(NPPluginFuncs* plugin_funcs);
  return InitializePluginFunctions(plugin_funcs);
}

NPError InitializePluginFunctions(NPPluginFuncs* plugin_funcs) {
  memset(plugin_funcs, 0, sizeof(*plugin_funcs));
  plugin_funcs->version = NPVERS_HAS_PLUGIN_THREAD_ASYNC_CALL;
  plugin_funcs->size = sizeof(*plugin_funcs);
  plugin_funcs->newp = NPP_New;
  plugin_funcs->destroy = NPP_Destroy;
  plugin_funcs->setwindow = NPP_SetWindow;
  plugin_funcs->event = NPP_HandleEvent;
  plugin_funcs->getvalue = NPP_GetValue;
  return NPERR_NO_ERROR;
}

NPError NP_Initialize(NPNetscapeFuncs* browser_functions,
                      NPPluginFuncs* plugin_functions) {
  return NP_GetEntryPoints(plugin_functions);
}

NPError NP_Shutdown() {
  return NPERR_NO_ERROR;
}

}  // extern "C"
}
