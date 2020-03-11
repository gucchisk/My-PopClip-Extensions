use HexString;

my $text = $ENV{POPCLIP_TEXT};
print HexString::fromHex($text);
