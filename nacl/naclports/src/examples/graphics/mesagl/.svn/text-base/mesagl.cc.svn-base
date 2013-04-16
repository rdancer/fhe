/*
 * Copyright (c) 2010 The Native Client SDK Authors. All rights reserved.
 * Use of this source code is governed by a BSD-style license that be
 * found in the LICENSE file.
 */

// This example uses the Mesa OpenGL rendering library to render into a
// Pepper 2D framebuffer.  It also demonstrates use of vertex buffer objects
// as specified in the OpenGL specification:
//     http://www.opengl.org/documentation/specs/
// Note that Mesa OpenGL provides software rendering and rasterization only.
// The only way to get hardware accelerated 3D graphics is through Pepper 3D and
// OpenGL ES 2.0.

#include <assert.h>
#include <errno.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <GL/gl.h>
#include <GL/glext.h>
#include <GL/osmesa.h>
#include <nacl/nacl_npapi.h>
#include <nacl/npapi_extensions.h>
#include <nacl/npupp.h>


//-----------------------------------------------------------------------------
//
// The module code.  This section implements the application code.  There are
// three other sections that implement the NPAPI module loading and browser
// bridging code.
//
//-----------------------------------------------------------------------------

void FlushCallback(NPP instance, NPDeviceContext* context,
                   NPError err, void* user_data) {
}

// The Surface class owns a bitmap which is a Pepper 2D context, and owns the
// Mesa OpenGL context that renders into that bitmap.
class Surface {
 public:
  explicit Surface(NPP npp);
  ~Surface();
  bool CreateContext(int width, int height);
  void DestroyContext();
  bool MakeCurrentContext() const {
    return OSMesaMakeCurrent(mesa_context_,
                             pixels(),
                             GL_UNSIGNED_BYTE,
                             width(),
                             height()) == GL_TRUE;
  }

  bool IsContextValid() const {
    return context2d_.region != NULL;
  }
  void Flush();
  int width() const {
    return width_;
  }
  int height() const {
    return height_;
  }
  void* pixels() const {
    return context2d_.region;
  }

 private:
  NPP npp_;
  NPExtensions* extensions_;
  int width_;
  int height_;
  NPDevice* device2d_;  // The PINPAPI 2D device.
  NPDeviceContext2D context2d_;  // The PINPAPI 2D drawing context.
  // Mesa specific
  OSMesaContext mesa_context_;
};

Surface::Surface(NPP npp)
    : npp_(npp),
      extensions_(NULL),
      width_(0),
      height_(0),
      device2d_(NULL),
      mesa_context_(0) {
  memset(&context2d_, 0, sizeof(context2d_));
  NPN_GetValue(npp_, NPNVPepperExtensions, &extensions_);
  assert(extensions_);
}

Surface::~Surface() {
  DestroyContext();
}

bool Surface::CreateContext(int width, int height) {
  if (IsContextValid())
    return true;
  // Acquire and initilaize the Pepper 2D device that Mesa use as its rendering
  // surface.
  assert(extensions_);
  device2d_ = extensions_->acquireDevice(npp_, NPPepper2DDevice);
  assert(device2d_);
  NPDeviceContext2DConfig config;
  NPError init_err = device2d_->initializeContext(npp_, &config, &context2d_);
  assert(NPERR_NO_ERROR == init_err);

  // Create a Mesa OpenGL context, bind it to the Pepper 2D context from above.
  width_ = width;
  height_ = height;
  mesa_context_ = OSMesaCreateContext(OSMESA_BGRA, NULL);
  if (0 == mesa_context_) {
    printf("OSMesaCreateContext failed!\n");
    DestroyContext();
    return false;
  }
  if (!MakeCurrentContext()) {
    printf("OSMesaMakeCurrent failed!\n");
    DestroyContext();
    return false;
  }
  // check for vertex buffer object support in OpenGL
  const char *extensions = reinterpret_cast<const char*>
      (glGetString(GL_EXTENSIONS));
  printf("OpenGL:supported extensions: %s\n", extensions);
  if (NULL != strstr(extensions, "GL_ARB_vertex_buffer_object")) {
    printf("OpenGL: GL_ARB_vertex_buffer_object available.\n");
  } else {
    // vertex buffer objects aren't available...
    printf("OpenGL: GL_ARB_vertex_buffer_object is not available.\n");
    printf("Vertex buffer objects are required for this demo,\n");
    DestroyContext();
    return false;
  }
  printf("OpenGL: Mesa context created.\n");
  return true;
}

void Surface::DestroyContext() {
  if (0 != mesa_context_) {
    OSMesaDestroyContext(mesa_context_);
    mesa_context_ = 0;
  }
  printf("OpenGL: Mesa context destroyed.\n");
  if (!IsContextValid())
    return;
  device2d_->destroyContext(npp_, &context2d_);
  memset(&context2d_, 0, sizeof(context2d_));
}

void Surface::Flush() {
  if (!IsContextValid())
    return;
  NPDeviceFlushContextCallbackPtr callback =
      reinterpret_cast<NPDeviceFlushContextCallbackPtr>(&FlushCallback);
  device2d_->flushContext(npp_, &context2d_, callback, NULL);
}

// GLDemo is an object that responds to calls from the browser to do the 3D
// rendering.  It publishes an "update" method via the HasMethod() callback
// (see below).
class GLDemo : public NPObject {
 public:
  explicit GLDemo(NPP npp) : surf_(new Surface(npp)) {}
  ~GLDemo() {
    delete surf_;
  }

  void Display() {
    surf_->Flush();
  }
  // Build a simple vertex buffer object
  void Setup(int width, int height);

  // Called from the browser via Invoke() when the method name is "update".
  // All of the opengl rendering is done in this function.
  void Update();

 private:
  Surface *surf_;
  GLuint vbo_color_;
  GLuint vbo_vertex_;
};


void GLDemo::Setup(int width, int height) {
  if (!surf_->CreateContext(width, height)) {
    return;
  }
  if (!surf_->MakeCurrentContext()) {
    return;
  }
  const int num_vertices = 3;
  GLfloat triangle_colors[num_vertices * 3] = {
    1.0f, 0.0f, 0.0f,        // color0
    0.0f, 1.0f, 0.0f,        // color1
    0.0f, 0.0f, 1.0f,        // color2
  };
  GLfloat triangle_vertices[num_vertices * 3] = {
    0.0f, 1.0f, -2.0f,       // vertex0
    1.0f, -1.0f, -2.0f,      // vertex1
    -1.0f, -1.0f, -2.0f,     // vertex2
  };

  // build a vertex buffer object (vbo), copy triangle data
  glGenBuffers(1, &vbo_color_);
  printf("OpenGL:vbo_color_: %d\n", vbo_color_);
  glBindBuffer(GL_ARRAY_BUFFER, vbo_color_);
  glBufferData(GL_ARRAY_BUFFER, sizeof(GLfloat) * num_vertices * 3,
      triangle_colors, GL_STATIC_DRAW);
  // build a vertex buffer object, copy triangle data
  glGenBuffers(1, &vbo_vertex_);
  printf("OpenGL:vbo_vertex_: %d\n", vbo_vertex_);
  glBindBuffer(GL_ARRAY_BUFFER, vbo_vertex_);
  glBufferData(GL_ARRAY_BUFFER, sizeof(GLfloat) * num_vertices * 3,
      triangle_vertices, GL_STATIC_DRAW);
}

void GLDemo::Update() {
  if (!surf_->MakeCurrentContext()) {
    return;
  }
  // frame setup
  static float angle = 0.0f;
  glViewport(80, 0, 480, 480);
  glMatrixMode(GL_PROJECTION);
  glLoadIdentity();
  glFrustum(-1.0, 1.0, -1.0, 1.0, 1.0, 100.0);
  glClearColor(0, 0, 0, 0);
  glMatrixMode(GL_MODELVIEW);
  glLoadIdentity();
  glRotatef(angle, 0.0f, 0.0f, 1.0f);
  angle = angle + 0.1f;
  glClear(GL_COLOR_BUFFER_BIT);
  // enable color & vertex arrays
  glEnable(GL_COLOR_ARRAY);
  glEnable(GL_VERTEX_ARRAY);
  // render the vertex buffer object (created in Setup)
  glBindBuffer(GL_ARRAY_BUFFER, vbo_color_);
  glColorPointer(3, GL_FLOAT, 0, NULL);
  glBindBuffer(GL_ARRAY_BUFFER, vbo_vertex_);
  glVertexPointer(3, GL_FLOAT, 0, NULL);
  glDrawArrays(GL_TRIANGLES, 0, 3);
  glBindBuffer(GL_ARRAY_BUFFER, 0);
  // disable color & vertex arrays
  glDisable(GL_COLOR_ARRAY);
  glDisable(GL_VERTEX_ARRAY);
  // make sure everything renders into the framebuffer
  glFlush();
}

//-----------------------------------------------------------------------------
//
// The scripting bridge code.  These functions are published to the browser via
// the NPP_New() function and |np_class| (see later section), and are called
// from the browser to allocate, deallocate and query the GLDemo instance.
//
//-----------------------------------------------------------------------------

extern "C" {

// The following functions implement functions to be used with npruntime.
static NPObject* AllocateMesaGL(NPP npp, NPClass* npclass) {
  GLDemo* rv = new GLDemo(npp);
  return rv;
}

static void Deallocate(NPObject* obj) {
  GLDemo* const gldemo = static_cast<GLDemo*>(obj);
  delete gldemo;
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
    GLDemo* const gldemo = static_cast<GLDemo*>(obj);
    gldemo->Update();
    gldemo->Display();
  }
  NPN_MemFree(method_name);
  return rv;
}

NPClass np_class = {
  NP_CLASS_STRUCT_VERSION,
  AllocateMesaGL,
  Deallocate,
  NULL,  // Invalidate Not implemented.
  HasMethod,
  Invoke,
  NULL,  // InvokeDefault Not implemented.
  NULL,  // HasProperty Not implemented.
  NULL,  // GetProperty Not implemented.
  NULL,  // SetProperty Not implemented.
  NULL  // RemoveProperty Not implemented.
};

//-----------------------------------------------------------------------------
//
// The browser gateway code.  These functions are published to the browser via
// the InitializePluginFunctions() function, they are called from the browser
// to set up and manage a module instance.
//
//-----------------------------------------------------------------------------

// These functions are required by both the develop and publish versions,
// they are called when a module instance is first loaded, and when the module
// instance is finally deleted.  They must use C-style linkage.
NPError NPP_Destroy(NPP instance, NPSavedData** save) {
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }

  GLDemo* gldemo = static_cast<GLDemo*>(instance->pdata);
  if (gldemo != NULL) {
    NPN_ReleaseObject(gldemo);
  }
  return NPERR_NO_ERROR;
}

// NPP_GetScriptableInstance retruns the NPObject pointer that corresponds to
// NPPVpluginScriptableNPObject queried by NPP_GetValue() from the browser.
NPObject* NPP_GetScriptableInstance(NPP instance) {
  if (instance == NULL) {
    return NULL;
  }
  GLDemo* gldemo = static_cast<GLDemo*>(instance->pdata);
  return gldemo;
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

  GLDemo* const gldemo =
      static_cast<GLDemo*>(NPN_CreateObject(instance, &np_class));
  instance->pdata = gldemo;
  return NPERR_NO_ERROR;
}

NPError NPP_SetWindow(NPP instance, NPWindow* window) {
  if (instance == NULL) {
    return NPERR_INVALID_INSTANCE_ERROR;
  }
  if (window == NULL) {
    return NPERR_GENERIC_ERROR;
  }
  GLDemo* gldemo = static_cast<GLDemo*>(instance->pdata);
  if (gldemo != NULL) {
    gldemo->Setup(window->width, window->height);
  }
  return NPERR_NO_ERROR;
}

//-----------------------------------------------------------------------------
//
// The module loading code.  The browser calls these when first loading the
// module, and once when all instances of the module have been destroyed.
//
//-----------------------------------------------------------------------------

static NPError InitializePluginFunctions(NPPluginFuncs* plugin_funcs) {
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

NPError NP_GetEntryPoints(NPPluginFuncs* plugin_funcs) {
  return InitializePluginFunctions(plugin_funcs);
}

NPError NP_Initialize(NPNetscapeFuncs* browser_functions,
                      NPPluginFuncs* plugin_functions) {
  return NP_GetEntryPoints(plugin_functions);
}

NPError NP_Shutdown() {
  return NPERR_NO_ERROR;
}

}  // extern "C"
