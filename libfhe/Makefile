# Makefile --  Makefile for the FHE library

# Copyright © 2010 Jan Minář <rdancer@rdancer.org>
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License version 2 (two),
# as published by the Free Software Foundation.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

CC = gcc
CFLAGS += -std=c99 -ggdb -Wall -pg
LDFLAGS = -pg
LDLIBS = -ltommath

.PHONY: all
all: fhe

fhe: fhe.h

.PHONY: test
test: fhe
	./fhe

.PHONY: valgrind
valgrind: fhe
	valgrind --tool=memcheck --leak-check=full --show-reachable=yes \
	    ./fhe 1 2>&1

.PHONY: clean
clean:
	rm -f -- fhe
	rm -f -- ./*.o

.PHONY: dist-clean
dist-clean: clean
	rm -f -- core
