#REM @echo off
set anio=%date:~6,4%
set mes=%date:~3,2%
set dia=%date:~0,2%
set hora=%time:~0,2%
set hora=%hora: =0%
set minuto=%time:~3,2%
set segundo=%time:~6,2%
set datename=date

set db=mandrake_novedades
set dbcode=001
set user=root
set pass=142536

c:
cd C:\laragon\www\mandrake_novedades\db
IF exist respaldo ( cd respaldo ) ELSE ( mkdir respaldo && cd respaldo)

REM mysqldump --user=%user% --password=%pass% %db% > %db%_db_%anio%%mes%%dia%_%hora%%minuto%.sql
REM mysqldump -u %user% -p %db% --password=%pass% > %dbcode%_db_%anio%%mes%%dia%_%hora%%minuto%.sql
mysqldump -u %user% -p %db% --password=%pass% > %dbcode%_db_%RANDOM%.sql

REM del *.* /q
REM echo mysqldump -u %user% -p %db% --password=%pass% > %dbcode%_db_%anio%%mes%%dia%_%hora%%minuto%.sql >> %db%_db_%anio%%mes%%dia%_%hora%%minuto%.log
