// Copyright 2010 The Native Client SDK Authors.
// Use of this source code is governed by a BSD-style license that can
// be found in the LICENSE file.

/**
 * @fileoverview Parses an infix-notation expression string and returns the
 * expression in postfix notation as an array of operands and operators.
 */

var google = google || {};
google.expr = {};
google.load = function() {};

/**
 * Some useful constants.
 * @enum {number}
 */
google.expr.Constants = {
  e: Math.exp(1),
  pi: Math.atan2(1,1)*4
};

/**
 * Regular expression patterns that match various tokens.
 * @enum {RegExp}
 */
google.expr.TokenRegExp = {
  NUMBER: /^\d+(\.\d+)?/,
  OPERATOR: /^[\+\-\*\/\^]/,
  PAREN: /^[\(\)]/
};

/**
 * Token types.
 * @enum {number}
 */
google.expr.TokenType = {
  NOTYPE: 0,
  NUMBER: 1,
  OPERATOR: 2,
  PAREN: 3
};

/**
 * Operator precedence values.
 * @enum {number}
 */
google.expr.OperatorPrecedence = {
  ADD: 0,
  SUBRACT: 0,
  MULTIPLY: 1,
  DIVIDE: 1,
  EXPONENT: 2,
  NEGATE: 3
};

/**
 * Consume the next token in |infix|.  A token is either an operator or a
 * decimal number.  Modifies |infix| such that its offset points at the next
 * input character after the token.
 * @param {Object.<string,offset>} infix The infix-notation expression.
 * @return {Object<token,type,opt_precedence>} The next token, or null if an
 *     error occurred.
 * @private
 */
google.expr.nextToken_ = function(infix) {
  var token = infix.string.substr(infix.offset).match(
      google.expr.TokenRegExp.PAREN);
  if (token) {
    // Matched a parenthesis.
    infix.offset += token[0].length;
    return { token: token[0], type: google.expr.TokenType.PAREN };
  }
  token = infix.string.substr(infix.offset).match(
      google.expr.TokenRegExp.NUMBER);
  if (token) {
    // Matched a number.
    infix.offset += token[0].length;
    return { token: token[0], type: google.expr.TokenType.NUMBER };
  }
  token = infix.string.substr(infix.offset).match(
      google.expr.TokenRegExp.OPERATOR);
  if (token) {
    // Matched an operator.
    infix.offset += token[0].length;
    var precedence;
    switch (token[0]) {
    case '+':
      precedence = google.expr.OperatorPrecedence.ADD;
      break;
    case '-':
      precedence = google.expr.OperatorPrecedence.SUBRACT;
      break;
    case '*':
      precedence = google.expr.OperatorPrecedence.MULTIPLY;
      break;
    case '/':
      precedence = google.expr.OperatorPrecedence.DIVIDE;
      break;
    case '^':
      precedence = google.expr.OperatorPrecedence.EXPONENT;
      break;
    }
    return { token: token[0],
             type: google.expr.TokenType.OPERATOR,
             precedence: precedence };
  }
  return null;
}

/**
 * Parse the infix expression in |expr| and produce the postfix-notation
 * equivalent.  |infix| is modified in-place, if the offset property is
 * not the same as infix.string.length upon return, then an error occurred.
 * Throws a SyntaxError() if anything goes wrong.
 * @param {Object.<string,offset>} infix The infix-notation expression.
 * @return {Array.string} The expression in postfix notation.
 * @private
 */
google.expr.infixToPostfix_ = function(infix) {
  var rpn_out = [];  // Reverse Polish Notation version of the expression.
  var operator_stack = [];
  while (infix.offset < infix.string.length) {
    // Consume tokens until the end of the input string is reached.
    var token = google.expr.nextToken_(infix);
    if (!token || token.type == google.expr.TokenType.NOTYPE) {
      throw new SyntaxError("Unrecognized token.");
    }
    switch (token.type) {
    case google.expr.TokenType.NUMBER:
      rpn_out.push(token.token);
      break;
    case google.expr.TokenType.OPERATOR:
      while (operator_stack.length > 0) {
        // Only handle left-associative operators.  Pop off all the operators
        // that have less or equal precedence to |token|.
        var operator = operator_stack.pop();
        if (operator.type == google.expr.TokenType.OPERATOR &&
            operator.precedence <= token.precedence) {
          rpn_out.push(operator.token);
        } else {
          // The top-of-stack operator has a higher precedence than the current
          // token, or it's a parenthesis.  Push it back on the stack and stop.
          operator_stack.push(operator);
          break;
        }
      }
      operator_stack.push(token);
      break;
    case google.expr.TokenType.PAREN:
      if (token.token == '(') {
        operator_stack.push(token);
      } else {
        // Pop operators off the stack until a matching '(' is found.
        var matched_paren = false;
        while (operator_stack.length > 0) {
          var operator = operator_stack.pop();
          if (operator.token == '(') {
            matched_paren = true;
            break;
          }
          rpn_out.push(operator.token);
        }
        if (!matched_paren) {
          throw new SyntaxError("Mis-matched parentheses.");
        }
      }
      break;
    }
  }
  // Pop all the remaining operators to the RPN output.
  while (operator_stack.length > 0) {
    var operator = operator_stack.pop();
    if (operator.type == google.expr.TokenType.PAREN) {
      throw new SyntaxError("Mis-matched parentheses.");
    }
    rpn_out.push(operator.token);
  }
  return rpn_out;
}

/**
 * Parse the infix expression in |expr| and produce the postfix-notation
 * equivalent.
 * @param {string} expr The infix-notation expression.
 * @return {Array.string} The expression in postfix notation.
 */
google.expr.parseExpression = function(expr) {
  var infix = {string: expr, offset: 0};
  var postfix = [];
  try {
    postfix = google.expr.infixToPostfix_(infix);
  } catch(e) {
    alert(e.message);
  }    
  return postfix;
}

