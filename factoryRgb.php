<?php
/**
 * Created by PhpStorm.
 * User: maimob
 * Date: 2017/8/23
 * Time: 上午10:57
 */
//让我们更深入工厂模式吧。继续如前，先建立一个能为本章节的其它部分持续举例说 明的简单类。 这是一个输出十六进制的HTML RGB Color类，包括了R, G, 和 B三个属性 (在构造对象的时候引入)和 getRgb()方法，getRgb()的作用是返回一个十六进制颜色的 字符串。
//和以前一样，我们按照测试驱动开发( TDD)的方法:写一个测试，再写一段代码满足该 测试，如果需要，反复下去。

class Color
{
    protected $r;
    protected $g;
    protected $b;

    public function __construct($r = 0, $g = 0, $b = 0)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    private function validateColor($color)
    {
        $check = (int)$color;
        if($check < 0 || $check > 255) {
            trigger_error("{$color} out of bounds");
        }else {
            return $check;
        }
    }

    public function Color($red = 0, $green = 0, $blue = 0)
    {
        $this->r = $this->validateColor($this->r);
        $this->g = $this->validateColor($this->g);
        $this->b = $this->validateColor($this->b);
    }

    public function getRgb()
    {
        return sprintf('#%02X%02X%02X',$this->r, $this->g, $this->b);
    }
}

class CrayonBox
{

    public function colorList()
    {
        return [
            'black' => [0, 0, 0],
            'green' => [0, 128, 0],
            'aqua'  => [0, 255, 255]
        ];
    }

    public static function getColor($color_name)
    {
        $color_name = strtolower($color_name);
        if(array_key_exists($color_name, $colors = self::colorList())) {
            $color = $colors[$color_name];
            return new Color($color[0], $color[1], $color[2]);
        }
        trigger_error("{$color_name} available?");
        return new Color();
    }
}

class testFactoryRgb extends PHPUnit_Framework_TestCase
{
    public function testInstantiate()
    {
        $this->assertInstanceOf('Color', $color = new Color());
        $this->assertTrue(method_exists($color, 'getRgb'));
    }

    public function testGetRgbWhite()
    {
        $white = new Color(255, 255, 255);
        $this->assertEquals('#FFFFFF', $white->getRgb());
    }

    public function testGetRegRed()
    {
        $red = new Color(255, 0, 0);

        $this->assertEquals('#FF0000', $red->getRgb());
    }

    public function testGetRandom()
    {
        $color = new Color(rand(0, 255), rand(0, 255), rand(0, 255));
        $this->assertRegExp('/^#[0-9A-F]{6}$/', $color->getRgb());
        $color2 = new Color($t = rand(0, 255), $t, $t);
        $this->assertRegExp('/^#([0-9A-F]{2})\1\1$/', $color2->getRgb());
    }

    public function testColorBoundaries()
    {
        $color = new Color(255);
        $color->Color();
    }

    public function testGetColor()
    {
        $this->assertInstanceOf('Color', $o = CrayonBox::getColor('BLACK'));
        $this->assertEquals('#000000', $o->getRgb());
    }

    public function testBadColor()
    {
        $this->assertInstanceOf('Color', $o = CrayonBox::getColor('black'));
        $this->assertEquals('#000000', $o->getRgb());
    }
}