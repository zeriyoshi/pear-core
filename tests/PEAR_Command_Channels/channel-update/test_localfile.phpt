--TEST--
channel-update command (local channel.xml)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
error_reporting(E_ALL);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'setup.php.inc';

$ch = new PEAR_ChannelFile;
$ch->setName('pear.php.net');
$ch->setSummary('fake');
$ch->setServer('pear.php.net');
$ch->setDefaultPEARProtocols();
$fp = fopen($temp_path . DIRECTORY_SEPARATOR . 'fakechannel.xml', 'wb');
fwrite($fp, $ch->toXml());
fclose($fp);
$e = $command->run('channel-update', array(), array($temp_path . DIRECTORY_SEPARATOR . 'fakechannel.xml'));
$phpunit->assertNoErrors('after');
$phpunit->assertEquals(array (
  0 =>
  array (
    'info' => 'Update of Channel "pear.php.net" succeeded',
    'cmd' => 'no command', 
  ),
), $fakelog->getLog(), 'log');

$reg = &new PEAR_Registry($temp_path . DIRECTORY_SEPARATOR . 'php');
$chan = $reg->getChannel('pear.php.net');
$phpunit->assertIsA('PEAR_ChannelFile', $chan, 'updated ok?');
$phpunit->assertEquals('pear.php.net', $chan->getName(), 'name ok?');
$phpunit->assertEquals('fake', $chan->getSummary(), 'summary ok?');
echo 'tests done';
?>
--EXPECT--
tests done
