/*
 * Copyright © 2010 Jan Minář <rdancer@rdancer.org>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2 (two),
 * as published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

#ifndef FHE_GLOBAL_H
#define FHE_GLOBAL_H



#include <gmp.h>
#include <gmpxx.h>


#include <QtCore/qglobal.h>

#if defined(FHE_LIBRARY)
#  define FHESHARED_EXPORT Q_DECL_EXPORT
#else
#  define FHESHARED_EXPORT Q_DECL_IMPORT
#endif

#endif // FHE_GLOBAL_H
