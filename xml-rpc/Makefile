# Makefile --  Makefile for the FHE evaluator XML-RPC server

# Copyright 2010, The Native Client SDK Authors. All rights reserved.
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


.PHONY: test
test:
	xmlrpc http://localhost:8080/RPC2 test.add i/37 i/5 \
	    | tee /dev/fd/2 | grep 'Integer: 42' >/dev/null
