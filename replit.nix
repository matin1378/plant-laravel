{ pkgs }: {
  deps = [
    pkgs.php
    pkgs.php82Extensions.mbstring
    pkgs.php82Extensions.xml
    pkgs.php82Extensions.curl
    pkgs.php82Extensions.zip
    pkgs.php82Extensions.sqlite3
    pkgs.unzip
    pkgs.nodejs_20
    pkgs.git
    pkgs.wget
    pkgs.cacert
  ];
}
