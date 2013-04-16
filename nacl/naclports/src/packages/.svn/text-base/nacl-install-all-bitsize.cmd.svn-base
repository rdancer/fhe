@echo off

setlocal

PATH=%PATH%;%~dp0..\third_party\cygwin\bin

ln -sfn %~dp0..\toolchain\win_x86\nacl /nacl 2>nul
ln -sfn %~dp0..\toolchain\win_x86\nacl64 /nacl64 2>nul

bash nacl-install-all-bitsize.sh %*
