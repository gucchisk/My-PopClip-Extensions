use Test::More;
use HexString;

@test_data = (['hello', '68656c6c6f'],
	      ['HELLO', '48454c4c4f'],
	      ['ハロー', 'e3838fe383ade383bc'],
	      ['はろー', 'e381afe3828de383bc'],
	      ['今日は', 'e4bb8ae697a5e381af']);

foreach my $data (@test_data) {
    ok(HexString::toHex(@$data[0]) eq @$data[1], "@${data[0]} - toHex");
    ok(HexString::fromHex(@$data[1]) eq @$data[0], "@${data[1]} - fromHex");
}

done_testing();