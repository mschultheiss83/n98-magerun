<?php

namespace N98\Magento\Command\System\Setup;

use Symfony\Component\Console\Tester\CommandTester;
use N98\Magento\Command\PHPUnit\TestCase;

class ChangeVersionCommandTest extends TestCase
{

    public function setUp()
    {
        ini_set('display_errors', 1);
    }
    public function testChangeVersion()
    {
        $command = $this->getMockBuilder('\N98\Magento\Command\System\Setup\ChangeVersionCommand')
            ->setMethods(array('_getResourceSingleton'))
            ->getMock();
        
        $resourceModel = $this->getMockBuilder('\Mage_Core_Model_Resource_Resource')
            ->disableOriginalConstructor()
            ->setMethods(array('setDbVersion', 'setDataVersion'))
            ->getMock();

        $command
            ->expects($this->once())
            ->method('_getResourceSingleton')
            ->will($this->returnValue($resourceModel));

        $resourceModel
            ->expects($this->once())
            ->method('setDbVersion')
            ->with('weee_setup', '1.6.0.0');

        $resourceModel
            ->expects($this->once())
            ->method('setDataVersion')
            ->with('weee_setup', '1.6.0.0');

        $application = $this->getApplication();
        $application->add($command);
        $command = $this->getApplication()->find('sys:setup:change-version');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'   => $command->getName(),
            'module'    => 'Mage_Weee',
            'version'   => '1.6.0.0'
        ));

        $this->assertContains(
            'Successfully updated Mage_Weee - weee_setup to version 1.6.0.0',
            $commandTester->getDisplay()
        );
    }

    public function testUpdateBySetupName()
    {
        $command = $this->getMockBuilder('\N98\Magento\Command\System\Setup\ChangeVersionCommand')
            ->setMethods(array('_getResourceSingleton'))
            ->getMock();

        $resourceModel = $this->getMockBuilder('\Mage_Core_Model_Resource_Resource')
            ->disableOriginalConstructor()
            ->setMethods(array('setDbVersion', 'setDataVersion'))
            ->getMock();

        $command
            ->expects($this->once())
            ->method('_getResourceSingleton')
            ->will($this->returnValue($resourceModel));

        $resourceModel
            ->expects($this->once())
            ->method('setDbVersion')
            ->with('weee_setup', '1.6.0.0');

        $resourceModel
            ->expects($this->once())
            ->method('setDataVersion')
            ->with('weee_setup', '1.6.0.0');

        $application = $this->getApplication();
        $application->add($command);
        $command = $this->getApplication()->find('sys:setup:change-version');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'   => $command->getName(),
            'module'    => 'Mage_Weee',
            'version'   => '1.6.0.0',
            'setup'     => 'weee_setup'
        ));

        $this->assertContains(
            'Successfully updated Mage_Weee - weee_setup to version 1.6.0.0',
            $commandTester->getDisplay()
        );
    }

    public function testSetupNameNotFound()
    {
        $command = $this->getMockBuilder('\N98\Magento\Command\System\Setup\ChangeVersionCommand')
            ->setMethods(array('_getResourceSingleton'))
            ->getMock();

        $resourceModel = $this->getMockBuilder('\Mage_Core_Model_Resource_Resource')
            ->disableOriginalConstructor()
            ->setMethods(array('setDbVersion', 'setDataVersion'))
            ->getMock();

        $command
            ->expects($this->once())
            ->method('_getResourceSingleton')
            ->will($this->returnValue($resourceModel));

        $application = $this->getApplication();
        $application->add($command);
        $command = $this->getApplication()->find('sys:setup:change-version');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'   => $command->getName(),
            'module'    => 'Mage_Weee',
            'version'   => '1.6.0.0',
            'setup'     => 'no_setup_exists'
        ));

        $this->assertContains(
            'Error no setup found with the name no_setup_exists',
            $commandTester->getDisplay()
        );
    }

    public function testModuleDoesNotExist()
    {
        $application = $this->getApplication();
        $application->add(new ChangeVersionCommand());
        $command = $this->getApplication()->find('sys:setup:change-version');

        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'   => $command->getName(),
            'module'    => 'I_DO_NOT_EXIST',
            'version'   => '1.0.0.0'
        ));

        $this->assertContains('No module found', $commandTester->getDisplay());
    }
}