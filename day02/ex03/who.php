#!/usr/bin/php
<?php

/*

Let's start from beginning:

% hexdump -C /var/run/utmpx
...
000004e0  00 00 00 00 00 00 00 00  6f 61 64 68 65 73 69 76  |........oadhesiv|
000004f0  00 00 00 00 00 00 00 00  00 00 00 00 00 00 00 00  |................|
*
000005e0  00 00 00 00 00 00 00 00  2f 00 01 01 63 6f 6e 73  |......../...cons|
000005f0  6f 6c 65 00 00 00 00 00  00 00 00 00 00 00 00 00  |ole.............|
00000600  00 00 00 00 00 00 00 00  00 00 00 00 bf f5 00 00  |................|
00000610  07 00 00 00 47 1c 9f 5d  19 d8 03 00 00 00 00 00  |....G..]........|
00000620  00 00 00 00 00 00 00 00  00 00 00 00 00 00 00 00  |................|
*
00000750  00 00 00 00 00 00 00 00  00 00 00 00 6f 61 64 68  |............oadh|
00000760  65 73 69 76 00 00 00 00  00 00 00 00 00 00 00 00  |esiv............|
00000770  00 00 00 00 00 00 00 00  00 00 00 00 00 00 00 00  |................|
*
00000850  00 00 00 00 00 00 00 00  00 00 00 00 73 30 30 30  |............s000|
00000860  74 74 79 73 30 30 30 00  00 00 00 00 00 00 00 00  |ttys000.........|
00000870  00 00 00 00 00 00 00 00  00 00 00 00 00 00 00 00  |................|
00000880  4f 79 01 00 07 00 00 00  55 1c 9f 5d be 71 00 00  |Oy......U..].q..|
00000890  00 00 00 00 00 00 00 00  00 00 00 00 00 00 00 00  |................|
*
000009d0  6f 61 64 68 65 73 69 76  00 00 00 00 00 00 00 00  |oadhesiv........|
...

% man endutxent
...
*/
// struct utmpx {
//   char ut_user[_UTX_USERSIZE];    /* login name */
//   char ut_id[_UTX_IDSIZE];        /* id */
//   char ut_line[_UTX_LINESIZE];    /* tty name */
//   pid_t ut_pid;                   /* process id creating the entry */
//   short ut_type;                  /* type of this entry */
//   struct timeval ut_tv;           /* time entry was created */
//   char ut_host[_UTX_HOSTSIZE];    /* host name */
//   __uint32_t ut_pad[16];          /* reserved for future use */
// };
/*

My login occurs on 0x4e8 (1256 dec) offset first, then on 0x75c (1884 dec).
So struct size is 628 bytes long. Pleasingly first one also starts at exact offset,
instead of strange offset.

_UTX_USERSIZE seems to be 256 bytes long (0x5e8 - 0x4e8)
_UTX_IDSIZE               4   bytes long (0x5ec - 0x5e8)
_UTX_LINESIZE             32  bytes long (0x60c - 0x5ec)
pid_t                     4   bytes long (per sys/types.h)
short                     2   bytes long (should be, but it is actually 4 bytes long :PepeRain:)

% man gettimeofday
...
*/
// struct timeval {
//   time_t      tv_sec;     /* seconds */
//   suseconds_t tv_usec;    /* microseconds */
// };
/*

time_t                    8 bytes long (per sys/types.h)

Well, let's dance then!

*/

$utmpx = fopen('/var/run/utmpx', 'rb');
if (!$utmpx) {
  return;
}

date_default_timezone_set('Europe/Moscow');

while (1) {
  $data = fread($utmpx, 628);
  if (!$data) {
    break;
  }

  $who_username = substr($data, 0, 256);
  $who_id = substr($data, 256, 4);
  $who_console = substr($data, 260, 32);

  $who_pid = substr($data, 292, 4);
  $who_pid = unpack('S', $who_pid)[1];

  $who_type = substr($data, 296, 4);
  $who_type = unpack('S', $who_type)[1];

  if ($who_type !== 7) {
    continue;
  }

  $who_time = substr($data, 300, 8);
  $who_time = unpack('L', $who_time)[1];
  $who_time = date('M d H:i', $who_time);
  
  echo $who_username . ' ' . $who_console . '  ' . $who_time . "\n";
}

fclose($utmpx);
