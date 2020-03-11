use HexString;

my $text = $ENV{POPCLIP_TEXT};
# my $text = 'ハロー';
print HexString::toHex($text);
