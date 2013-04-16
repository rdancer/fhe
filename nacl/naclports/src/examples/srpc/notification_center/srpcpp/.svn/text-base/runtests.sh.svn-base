#!/bin/bash

echo "Running all tests in `pwd`"

SEL_LDR="SEL_LDR NOT FOUND"

if [ ! -n "$1" ] 
then
  echo "sel_ldr not provided as argument, attempting to locate it."
  #check for sel_ldr in path
  SEL_LDR=`which sel_ldr`
  
  if [ "x${SEL_LDR}" == "x" ]
  then
    echo "sel_ldr binary not found in path.";
    echo "Please enter complete path to sel_ldr:"
    read SEL_LDR
  fi
else
  echo "found arg"
  SEL_LDR=$1;
fi

MATCH=`expr match "$SEL_LDR" '.*sel_ldr'`

if [ "$MATCH" -ne "0" ]
then
  echo "using sel_ldr: ${SEL_LDR}"

  for i in `ls *test*\.nexe`;
  do 
    echo "Running: "$i; 
    $SEL_LDR $i  || exit 1
    echo "Test finished successfully, moving on..."
  done
  echo "All tests appear to have finished without major problems."
else
  echo "Could not find sel_ldr via argument, PATH or user input."
  echo "Recommended Usage: runtests.sh PATH_TO_SEL_LDR"
fi
