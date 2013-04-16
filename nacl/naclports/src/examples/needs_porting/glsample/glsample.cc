/*
 * Copyright (c) 2009 The Native Client Authors. All rights reserved.
 * Use of this source code is governed by a BSD-style license that be
 * found in the LICENSE file.
 */

// NaCl Drawing demo
//   Uses OpenGL rendering library to render into a Native Client framebuffer.
//   Demonstrates use of vertex buffer objects.
//

#include <errno.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <GL/gl.h>
#include <GL/glext.h>
#include <GL/osmesa.h>
#include <nacl/nacl_av.h>
#include <nacl/nacl_srpc.h>


// global properties used to setup demo
static const int kMaxWindow = 4096;
static const int kMaxFrames = 10000000;
static int g_window_width = 512;
static int g_window_height = 512;
static int g_num_frames = 9999;

// Mesa specific
static OSMesaContext g_mesa_context = 0;

// Simple Surface structure to hold a raster rectangle
struct Surface {
  int width, height, pitch;
  uint32_t *pixels;
  Surface(int w, int h) { width = w;
                          height = h;
                          pitch = w;
                          pixels = new uint32_t[width * height]; }
  ~Surface() { delete[] pixels; }
};


// Drawing class holds information and functionality needed to render
class GLDemo {
 public:
  void Display();
  bool PollEvents();
  void Setup();
  void Update();
  explicit GLDemo(Surface *s);
  ~GLDemo();

 private:
  Surface *surf_;
  GLuint vbo_color_;
  GLuint vbo_vertex_;
};


// Setup builds a simple vertex buffer object
void GLDemo::Setup() {
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


// This update loop is run once per frame.
// All of the opengl rendering is done in this function.
void GLDemo::Update() {
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


// Displays software rendered image on the screen
void GLDemo::Display() {
  int r;
  r = nacl_video_update(surf_->pixels);
  if (-1 == r) {
    printf("nacl_video_update() returned %d\n", errno);
  }
}


// Polls events and services them.
bool GLDemo::PollEvents() {
  NaClMultimediaEvent event;
  while (0 == nacl_video_poll_event(&event)) {
    if (event.type == NACL_EVENT_QUIT) {
      return false;
    }
  }
  return true;
}


// Sets up and initializes GLDemo.
GLDemo::GLDemo(Surface *surf) {
  surf_ = surf;
}


// Frees up resources.
GLDemo::~GLDemo() {
}


// Runs the demo and animate the image for kNumFrames
void RunDemo(Surface *surface) {
  GLDemo demo(surface);
  demo.Setup();
  for (int i = 0; i < g_num_frames; ++i) {
    demo.Update();
    demo.Display();
    printf("Frame: %04d\b\b\b\b\b\b\b\b\b\b\b", i);
    fflush(stdout);
    if (!demo.PollEvents())
      break;
  }
}


// Frees window buffer.
void Shutdown(Surface *surface) {
  if (0 != g_mesa_context) {
    OSMesaDestroyContext(g_mesa_context);
    g_mesa_context = 0;
  }
  delete surface;
  nacl_video_shutdown();
  nacl_multimedia_shutdown();
}


// Initializes a window buffer.
Surface* Initialize() {
  int r;
  int width;
  int height;
  r = nacl_multimedia_init(NACL_SUBSYSTEM_VIDEO | NACL_SUBSYSTEM_EMBED);
  if (-1 == r) {
    printf("Multimedia system failed to initialize!  errno: %d\n", errno);
    exit(-1);
  }
  // if this call succeeds, use width & height from embedded html
  r = nacl_multimedia_get_embed_size(&width, &height);
  if (0 == r) {
    g_window_width = width;
    g_window_height = height;
  }
  r = nacl_video_init(g_window_width, g_window_height);
  if (-1 == r) {
    printf("Video subsystem failed to initialize!  errno; %d\n", errno);
    exit(-1);
  }
  Surface *surface = new Surface(g_window_width, g_window_height);

  // create a Mesa OpenGL context
  g_mesa_context = OSMesaCreateContext(OSMESA_BGRA, NULL);
  if (0 == g_mesa_context) {
    printf("OSMesaCreateContext failed!\n");
    Shutdown(surface);
    exit(-1);
  }
  // bind Mesa's OpenGL output to Native Client surface
  if (GL_FALSE == OSMesaMakeCurrent(g_mesa_context, surface->pixels,
      GL_UNSIGNED_BYTE, surface->width, surface->height)) {
    printf("OSMesaMakeCurrent failed!\n");
    Shutdown(surface);
    exit(-1);
  }
  // check for vertex buffer object support in OpenGL
  const char *extensions = reinterpret_cast<const char *>
      (glGetString(GL_EXTENSIONS));
  printf("OpenGL:supported extensions: %s\n", extensions);
  if (NULL != strstr(extensions, "GL_ARB_vertex_buffer_object")) {
    printf("OpenGL: GL_ARB_vertex_buffer_object available.\n");
  } else {
    // vertex buffer objects aren't available...
    printf("OpenGL: GL_ARB_vertex_buffer_object is not available.\n");
    printf("Vertex buffer objects are required for this demo,\n");
    Shutdown(surface);
    exit(-1);
  }
  return surface;
}


// If user specified options on cmd line, parse them
// here and update global settings as needed.
void ParseCmdLineArgs(int argc, char **argv) {
  // look for cmd line args
  if (argc > 1) {
    for (int i = 1; i < argc; i++) {
      if (0 == strncmp(argv[i], "-w", 2)) {
        int w = atoi(&argv[i][2]);
        if ((w > 0) && (w < kMaxWindow)) {
          g_window_width = w;
        }
      } else if (0 == strncmp(argv[i], "-h", 2)) {
        int h = atoi(&argv[i][2]);
        if ((h > 0) && (h < kMaxWindow)) {
          g_window_height = h;
        }
      } else if (0 == strncmp(argv[i], "-f", 2)) {
        int f = atoi(&argv[i][2]);
        if ((f > 0) && (f < kMaxFrames)) {
          g_num_frames = f;
        }
      } else {
        printf("GLDemo\n");
        printf("usage: -w<n>   width of window.\n");
        printf("       -h<n>   height of window.\n");
        printf("       -f<n>   number of frames.\n");
        printf("       --help  show this screen.\n");
        exit(0);
      }
    }
  }
}


// Parses cmd line options, initializes surface, runs the demo & shuts down.
int main(int argc, char **argv) {
  ParseCmdLineArgs(argc, argv);
  Surface *surface = Initialize();
  RunDemo(surface);
  Shutdown(surface);
  return 0;
}
