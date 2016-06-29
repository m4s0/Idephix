<?php
namespace Idephix;

class EnvironmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_give_array_access()
    {
        $context = Environment::fromArray(array('foo' => 'bar'));
        $this->assertEquals('bar', $context['foo']);

        $context = Environment::fromArray(array());
        $context['foo'] = 'bar';
        $this->assertEquals('bar', $context['foo']);
    }

    /**
     * @test
     */
    public function it_should_allow_default_vaule()
    {
        $context = Environment::fromArray(array('foo' => 'bar'));
        $this->assertEquals('i-am-default', $context->get('not-present', 'i-am-default'));
    }

    /**
     * @test
     */
    public function it_should_allow_to_retrieve_data_using_dot_notation()
    {
        $context = Environment::fromArray(
            array(
                'targets' => array(
                    'prod' => array(
                        'release_dir' => '/var/www'
                    )
                ),
            )
        );

        $this->assertEquals(array('release_dir' => '/var/www'), $context['targets.prod']);
        $this->assertEquals('/var/www', $context['targets.prod.release_dir']);
    }

    /**
     * @test
     */
    public function it_should_allow_to_set_data_using_dot_notation()
    {
        $context = Environment::fromArray(array());
        $context['targets'] = array('prod' => array('release_dir' => '/var/www'));

        $this->assertEquals('/var/www', $context['targets.prod.release_dir']);
    }

    /**
     * @test
     */
    public function it_should_return_null_for_non_existing_key()
    {
        $context = Environment::fromArray(array('targets' => array('prod' => array('release_dir' => '/var/www'))));

        $this->assertNull($context['sshClient']);
    }

    /**
     * @test
     */
    public function it_should_allow_to_resolve_callable_elements()
    {
        $spy = new EnvironmentSpy();
        $context = Environment::fromArray(
            array(
                'foo' => function () use ($spy) {
                    $spy->resolved = true;
                    return 'bar';
                }
            )
        );

        $this->assertFalse($spy->resolved);
        $this->assertEquals('bar', $context['foo']);
        $this->assertTrue($spy->resolved);
    }
}

class EnvironmentSpy
{
    public $resolved = false;
}