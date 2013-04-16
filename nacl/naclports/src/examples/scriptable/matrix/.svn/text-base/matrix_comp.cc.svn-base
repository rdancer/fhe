// Copyright 2008 The Native Client SDK Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

#include <cstdio>
#include <cstdlib>
#include <cstring>

#include <vector>
#include <sstream>

#include <nacl/nacl_npapi.h>
#include <nacl/nacl_srpc.h>

#include "examples/scriptable/matrix/matrix_comp.h"

#include "boost/numeric/ublas/matrix.hpp"
#include "boost/numeric/ublas/lu.hpp"
#include "boost/numeric/ublas/io.hpp"


// constructor for the MatrixCompute class -- does some logging for debug
MatrixCompute::MatrixCompute()
    : Scriptable() {
  printf("MatrixCompute: MatrixCompute() was called!\n");
  fflush(stdout);
}

// destructor for the MatrixCompute class
MatrixCompute::~MatrixCompute() {
  printf("MatrixCompute: ~MatrixCompute() was called!\n");
  fflush(stdout);
}

//
// Given an NPString |npString|, mallocs a char array, copies it, adds
//   null terminator
// NOTE: This malloc's memory -- caller should free it when done
//
std::string CreateString(const NPString& npString) {
  std::string ret_value;
  for (unsigned int i = 0; i < npString.UTF8Length; ++i) {
    ret_value += npString.UTF8Characters[i];
  }
  return ret_value;
}

// Converts |value| to a NPVariant and sets the |result|
// to that value.  This is used for returning a string
// from C++ back to Javascript
void SetReturnStringVal(NPVariant* result, const std::string& value) {
  unsigned int msg_length = strlen(value.c_str()) + 1;
  char *msg_copy = reinterpret_cast<char*>(NPN_MemAlloc(msg_length));
  // Note: |msg_copy| will be freed later on by the browser, so it needs to
  // be allocated here with NPN_MemAlloc().
  strncpy(msg_copy, value.c_str(), msg_length);
  STRINGN_TO_NPVARIANT(msg_copy, msg_length - 1, *result);
}

// Converts an NPVariant to a number of type T.
// Type <class T> would normally be an int, float, double, unsigned int, etc.
// and so whether NPVARIANT_TO_INT32 or NPVARIANT_TO_DOUBLE is used,
// we end up with it static_cast to the desired type...
template <class T>
class NPVariantToNumber {
  public:
    NPVariantToNumber() : conversion_error_(false) { }

    // functor that tries to convert |arg| to a number
    // Returns a value of type |T|
    // Indicates an error occurred by setting |conversion_error_| to true
    //   and putting an explanation in |error_message_|
    T operator()(const NPVariant arg) {
       // clear out old/existing error & message
       conversion_error_ = false;
       error_message_ = "";
       if (NPVARIANT_IS_INT32(arg)) {
         return static_cast<T>(NPVARIANT_TO_INT32(arg));
       } else if (NPVARIANT_IS_DOUBLE(arg)) {
         return static_cast<T>(NPVARIANT_TO_DOUBLE(arg));
       } else if (NPVARIANT_IS_STRING(arg)) {
         // This lets us pass strings or number from Javascript.
         // Although slower, it only gets called when strings are being used.
         NPString arg_string = NPVARIANT_TO_STRING(arg);
         std::string result = CreateString(arg_string);
         std::stringstream ss(result);
         T val = 0;
         if ((ss >> val).fail()) {
            std::ostringstream oss;
            oss << "NPVariantToNumber::Error converting " << result
                << " to a number";
            error_message_ = oss.str();
            printf("%s", error_message_.c_str());
            conversion_error_ = true;
         }
         return val;
       }
       // error case
       error_message_ = "Unhandled NPVARIANT type";
       conversion_error_ = true;
       return 0;
    }
    // Getters for conversion_error_ and error_message_
    bool conversion_error() const { return conversion_error_;}
    std::string error_message() const { return error_message_;}
  private:
    bool conversion_error_;
    std::string error_message_;
};

//
// InvertMatrix -- this function is copied from the Effective UBLAS page
//   for boost libraries:
// http://www.crystalclearsoftware.com/cgi-bin/boost_wiki/wiki.pl?Effective_UBLAS
// The algorithm is here:
// http://www.crystalclearsoftware.com/cgi-bin/boost_wiki/wiki.pl?LU_Matrix_Inversion
// and is based on "Numerical Recipies in C", 2nd ed.,
// by Press, Teukolsky, Vetterling & Flannery.
//
template<class T>
bool InvertMatrix(const boost::numeric::ublas::matrix<T>& input,
  boost::numeric::ublas::matrix<T>& inverse) {
  // create a working copy of the input
  boost::numeric::ublas::matrix<T> A(input);
  // create a permutation matrix for the LU-factorization
  typedef boost::numeric::ublas::permutation_matrix<std::size_t> pmatrix;
  pmatrix pm(A.size1());

  // perform LU-factorization
  int res = boost::numeric::ublas::lu_factorize(A, pm);
  if (res != 0)
    return false;

  // create identity matrix of "inverse"
  inverse.assign(boost::numeric::ublas::identity_matrix<T> (A.size1()));

  // backsubstitute to get the inverse
  boost::numeric::ublas::lu_substitute(A, pm, inverse);
  return true;
}

// Convert a Boost matrix |boost_matrix| to a simple html table
// Uses ostringstream to write a mix of strings and numbers into a stream,
//  and then convert it all to a string
// The |title| and |border| inputs are for formatting the table
// The html table is returned as a string
std::string ConvertMatrixToHtml(
    std::string title, unsigned int border,
    boost::numeric::ublas::matrix<double> boost_matrix ) {
  std::ostringstream ss;
  ss << title << "<BR><table border=\"" << border << "\">";
  for (unsigned int i = 0; i < boost_matrix.size1(); ++i) {
    ss <<  "<tr>";
    for (unsigned int j = 0; j < boost_matrix.size2(); ++j) {
      ss << " <td>" << boost_matrix(i, j) << " </td>";
    }
    ss <<  "</tr>";
  }
  ss << "</table>";
  return ss.str();
}


// Called by Javascript -- this is a static method to process the matrix that
// is passed as a Javascript array object.  The array object is a 1-d array
// which whose first two values are the width and height of the matrix, and
// then the rest of the values are the 2D matrix  contents.
bool MatrixCompute::ComputeUsingArray(Scriptable* instance,
                                      const NPVariant* args,
                                      uint32_t arg_count,
                                      NPVariant* result) {
  printf("MatrixCompute::ComputeUsingArray, arg_count=%ld\n", arg_count);
  fflush(stdout);

  // There should be exactly one arg, which should be an object
  if (arg_count != 1) {
    printf("Unexpected number of args\n");
    SetReturnStringVal(result, "Unexpected number of args");
    return true;
  }
  if (!NPVARIANT_IS_OBJECT(args[0])) {
    printf("Arg from Javascript is not an object!\n");
    SetReturnStringVal(result, "Arg from Javascript is not an object!");
    return true;
  }

  NPObject* arg_object = NPVARIANT_TO_OBJECT(args[0]);
  NPVariant npv_length;
  NPN_GetProperty(instance->npp(), arg_object,
                  NPN_GetStringIdentifier("length"), &npv_length);

  // The length should be > 2 since we always have a width and height plus data
  if (npv_length.value.intValue <= 2) {
    // then we didn't get width, height, and then the cell values...
    printf("Invalid length (%ld)\n", npv_length.value.intValue);
    return false;
  }

  NPVariantToNumber<int> int_conv_functor;
  double arg_value;
  NPVariant cur_value;

  // grab the width and height
  NPN_GetProperty(instance->npp(), arg_object, NPN_GetIntIdentifier(0),
                  &cur_value);
  int width = int_conv_functor(cur_value);
  if (int_conv_functor.conversion_error()) {
    SetReturnStringVal(result, "Error getting width from JS array:");
    return true;
  }

  NPN_GetProperty(instance->npp(), arg_object, NPN_GetIntIdentifier(1),
                  &cur_value);
  int height = int_conv_functor(cur_value);
  if (int_conv_functor.conversion_error()) {
    SetReturnStringVal(result, "Error getting height from JS array:");
    return true;
  }

  NPVariantToNumber<double> conv_functor;
  boost::numeric::ublas::matrix<double>  initial_matrix(height, width);
  // the transpose will have |width| rows....instead of |width| columns
  boost::numeric::ublas::matrix<double> trans_matrix(width, height);

  int row = 0, column = 0;

  // Fill the initial_matrix with values from the JS array object
  for (int i = 2; i < npv_length.value.intValue; ++i) {
    NPN_GetProperty(instance->npp(), arg_object, NPN_GetIntIdentifier(i),
                    &cur_value);
    arg_value = conv_functor(cur_value);
    if (conv_functor.conversion_error()) {
      // error case from Javascript
      SetReturnStringVal(result, conv_functor.error_message());
      return true;
    } else {
      // got good value from Javascript array
      printf("Grabbed %g from array for %d,%d\n", arg_value, column, row);
      fflush(stdout);
      initial_matrix(row, column) = arg_value;
      ++column;
      if (column == width) {
        column = 0;
        ++row;
      }
    }
  }

  // Note -- throughout the program I pass in various values for the 3rd
  // argument |border| which controls the size of the border in the
  // html string that is created by ConvertMatrixToHtml.  There is not a
  // special reason for the different values -- but I wanted to call it
  // with different values so that the result visual effect in html can
  // be seen on the matrix.html web page.
  std::string full_message = ConvertMatrixToHtml(
    "Orig matrix from JS array", 1, initial_matrix);

  // transpose the matrix
  trans_matrix = boost::numeric::ublas::trans(initial_matrix);

  full_message += ConvertMatrixToHtml("Transposed matrix from JS array",
    2, trans_matrix);

  SetReturnStringVal(result, full_message);
  return true;
}

// Called by Javascript -- this is a static method to process the matrix that
// is passed as a series of doubles in |args|. The first two values are the
// width and height of the matrix -- the rest of the values are the array
// contents.
bool MatrixCompute::ComputeAnswer(Scriptable * instance,
                          const NPVariant* args,
                          uint32_t arg_count,
                          NPVariant* result) {
  std::vector<double> input_vector;
  double arg_value;

  if (arg_count < 2) {
    SetReturnStringVal(result, "Error in ComputeAnswer, arg_count too small");
    return true;
  }
  int matrix_width = 0, matrix_height = 0;

  NPVariantToNumber<int> int_conv_functor;
  matrix_width = int_conv_functor(args[0]);
  if (int_conv_functor.conversion_error()) {
    SetReturnStringVal(result, int_conv_functor.error_message());
    return true;
  }
  matrix_height = int_conv_functor(args[1]);
  if (int_conv_functor.conversion_error()) {
    SetReturnStringVal(result, int_conv_functor.error_message());
    return true;
  }

  if (matrix_width < 0 || matrix_height < 0) {
    SetReturnStringVal(result, "Invalid matrix sizes");
    return true;
  }

  NPVariantToNumber<double> conv_functor;
  for (unsigned int index = 2; index < arg_count; ++index) {
    const NPVariant arg = args[index];
    arg_value = conv_functor(arg);
    if (conv_functor.conversion_error()) {
      SetReturnStringVal(result, conv_functor.error_message());
      return true;
    } else {
      input_vector.push_back(arg_value);
    }
  }

  // filling matrix data
  boost::numeric::ublas::matrix<double>
    matrix_data(matrix_height, matrix_width);

  int column = 0, row = 0;
  for ( std::vector<double>::iterator iter = input_vector.begin();
        iter != input_vector.end(); ++iter) {
    double value = *iter;
    printf(" row %d column %d %3.3g ", row, column, value);
    matrix_data(row, column) = value;
    ++column;
    if (column >= matrix_width) {
      column = 0;
      ++row;
    }
  }

  printf("Filled matrix_data...\n");
  fflush(stdout);

  std::string js_message;   // message to send back to Javascript
  js_message = ConvertMatrixToHtml("Original Matrix:", 3, matrix_data);

  if ((matrix_height == matrix_width) && matrix_height>0) {
    std::string inverted_matrix_string;
    printf("Inverting...height=%d width=%d\n", matrix_height, matrix_width);
    fflush(stdout);

    boost::numeric::ublas::matrix<double>
      inverted_matrix(matrix_height, matrix_width);

    bool can_be_inverted = InvertMatrix(matrix_data, inverted_matrix);

    if (can_be_inverted) {
      inverted_matrix_string = ConvertMatrixToHtml("Inverted Matrix:", 4,
        inverted_matrix);
      // now let's multiply matrix_data X inverted_matrix and print it
      // to visually double-check the inverse
      boost::numeric::ublas::matrix<double>
        prod_matrix(matrix_height, matrix_width);

      prod_matrix = prod(matrix_data, inverted_matrix);

      inverted_matrix_string += ConvertMatrixToHtml(
        "Matrix Multiply -- Original Matrix x Inverse Matrix:", 4, prod_matrix);
    } else {
      inverted_matrix_string = "MATRIX CANNOT BE INVERTED";
    }
    js_message += inverted_matrix_string;
  }

  SetReturnStringVal(result, js_message);
  return true;
}

// Put the methods that are callable by Javascript into the method table
void MatrixCompute::InitializeMethodTable() {
  printf("MatrixCompute: MatrixCompute::InitializeMethodTable was called!\n");
  fflush(stdout);

  NPIdentifier matrix_array_id = NPN_GetStringIdentifier("ComputeUsingArray");
  IdentifierToMethodMap::value_type tArrayMethodEntry(matrix_array_id,
    &MatrixCompute::ComputeUsingArray);
  method_table_->insert(tArrayMethodEntry);

  NPIdentifier matrix_invert_id = NPN_GetStringIdentifier("ComputeAnswer");
  IdentifierToMethodMap::value_type tInvertMethodEntry(matrix_invert_id,
    &MatrixCompute::ComputeAnswer);
  method_table_->insert(tInvertMethodEntry);
}
