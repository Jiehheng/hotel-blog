#!/usr/bin/perl -w

use strict;
use warnings;
use CGI qw/:all/;

my $cgi = new CGI;

#文件保存目錄路徑
my $save_path = '../attached/';
#文件保存目錄URL
my $save_url = './attached/';
#定義允許上傳的文件擴展名
my $ext_arr = ['gif','jpg','png','bmp'];
#最大文件大小
my $max_size = 1000000;

my $file_name = $cgi->param('fileName');
my $file_data = $cgi->param('fileData');

if (not defined $file_data) {
	alert("上傳文件失敗。");
}
#文件的全部路徑
my $file_path = $save_path.$file_name;
#文件URL
my $file_url = $save_url.$file_name;

my @temp = split(/\./, $file_name);
my $file_ext = lc(pop(@temp));

if (in_array($file_name, @$ext_arr) eq 'NO') {
	alert("上傳文件擴展名是不允許的擴展名。");
}
my $data = '';
while (my $bytesread = read($file_data, my $buffer, 1024)) {
	$data .= $buffer;
}
if (length($data) > $max_size) {
	alert("上傳文件大小超過限制。");
}
my $result = fwrite($file_path, $data, 1);
if (not defined $result) {
	alert("上傳目錄不存在。");
}

#插入圖片，關閉層
print "Content-type: text/html\n\n";
print '<html>';
print '<head>';
print '<title>Insert Image</title>';
print '<meta http-equiv="content-type" content="text/html; charset=big5">';
print '</head>';
print '<body>';
print '<script type="text/javascript">parent.KindInsertImage("'.$file_url.'","'.$cgi->param('imgWidth').'","'.$cgi->param('imgHeight').'","'.$cgi->param('imgBorder').'","'.$cgi->param('imgTitle').'","'.$cgi->param('imgAlign').'","'.$cgi->param('imgHspace').'","'.$cgi->param('imgVspace').'");</script>';
print '</body>';
print '</html>';

sub in_array
{
	my $needle = shift;
	foreach (@_) {
		return 'YES' if ($needle eq $_);
	}
	return 'NO';
}
sub alert
{
	my $msg = shift;
	print "Content-type: text/html\n\n";
	print '<html>';
	print '<head>';
	print '<title>error</title>';
	print '<meta http-equiv="content-type" content="text/html; charset=big5">';
	print '</head>';
	print '<body>';
	print '<script type="text/javascript">alert("'.$msg.'");parent.KindDisableMenu();parent.KindReloadIframe();</script>';
	print '</body>';
	print '</html>';
	exit;
}
sub fwrite
{
	my ($path, $data, $binmode) = @_;
	my $flag = '>';
	if (open(FH, "$flag$path")) {
		binmode(FH) if (defined $binmode);
		if (flock(FH,2)) {
			print FH $data;
			flock(FH,8);
		}
		return 1;
	} else {
		return undef;
	}
}