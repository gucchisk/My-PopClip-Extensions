package HexString;

sub toHex {
    my $text = shift;
    return unpack("H*", $text);
}

sub fromHex {
    my $text = shift;
    return pack("H*", $text);
}
	
1;
