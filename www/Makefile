# Makefile -- make file for the web content of FHE
#
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


.PHONY: default
default: all

.PHONY: all
all: favicon.ico

favicon.ico: favicon.ppm
	ppmtowinicon -output favicon.ico favicon.ppm

.PHONY: clean
clean:
	# Do not remove -- is hard-linked
	#rm -f -- favicon.ico
	: > favicon.ico
