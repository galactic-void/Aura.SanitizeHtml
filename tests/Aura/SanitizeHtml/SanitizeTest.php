<?php
namespace Aura\SanitizeHtml;

/**
 * Test class for Sanitize.
 * Generated by PHPUnit on 2012-09-14 at 00:24:58.
 */
class SanitizeTest extends \PHPUnit_Framework_TestCase
{
    protected $sanitize;

    /**
     * http://ha.ckers.org/xss.html
     */
    protected $xss = [
        [
            'test'   => '\';alert(String.fromCharCode(88,83,83))//\\\';alert(String.fromCharCode(88,83,83))//";alert(String.fromCharCode(88,83,83))//\";alert(String.fromCharCode(88,83,83))//--></SCRIPT>">\'><SCRIPT>alert(String.fromCharCode(88,83,83))</SCRIPT>',
            'expect' => '&apos;;alert(String.fromCharCode(88,83,83))//\&apos;;alert(String.fromCharCode(88,83,83))//&quot;;alert(String.fromCharCode(88,83,83))//\&quot;;alert(String.fromCharCode(88,83,83))//--&gt;&quot;&gt;&apos;&gt;alert(String.fromCharCode(88,83,83))',
        ],
        [
            'test'   => '\'\';!--"<XSS>=&{()}',
            'expect' => '&apos;&apos;;!--&quot;=&amp;{()}',
        ],
        [
            'test'   => '<SCRIPT SRC=http://ha.ckers.org/xss.js></SCRIPT>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC="javascript:alert(\'XSS\');">',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC=javascript:alert(\'XSS\')>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC=JaVaScRiPt:alert(\'XSS\')>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC=javascript:alert(&quot;XSS&quot;)>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC=`javascript:alert("RSnake says, \'XSS\'")`>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG """><SCRIPT>alert("XSS")</SCRIPT>">',
            'expect' => 'alert(&quot;XSS&quot;)&quot;&gt;',
        ],
        [
            'test'   => '<IMG SRC=javascript:alert(String.fromCharCode(88,83,83))>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC=&#106;&#97;&#118;&#97;&#115;&#99;&#114;&#105;&#112;&#116;&#58;&#97;&#108;&#101;&#114;&#116;&#40;&#39;&#88;&#83;&#83;&#39;&#41;>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC=&#0000106&#0000097&#0000118&#0000097&#0000115&#0000099&#0000114&#0000105&#0000112&#0000116&#0000058&#0000097&#0000108&#0000101&#0000114&#0000116&#0000040&#0000039&#0000088&#0000083&#0000083&#0000039&#0000041>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC=&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x61&#x6C&#x65&#x72&#x74&#x28&#x27&#x58&#x53&#x53&#x27&#x29>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC="jav  ascript:alert(\'XSS\');">',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC="jav&#x09;ascript:alert(\'XSS\');">',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC="jav&#x0A;ascript:alert(\'XSS\');">',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC="jav&#x0D;ascript:alert(\'XSS\');">',
            'expect' => '',
        ],
        [
            'test'   => '<IMG
SRC
=
"
j
a
v
a
s
c
r
i
p
t
:
a
l
e
r
t
(
\'
X
S
S
\'
)
"
>',
            'expect' => '',
        ],
        [
            'test'   => "perl -e 'print \"<IMG SRC=java\0script:alert(\\\"XSS\\\")>\";' > out",
            'expect' => 'perl -e &apos;print &quot;&quot;;&apos; &gt; out',
        ],
        [
            'test'   => "perl -e 'print \"<SCR\0IPT>alert(\\\"XSS\\\")</SCR\0IPT>\";' > out",
            'expect' => 'perl -e &apos;print &quot;alert(\&quot;XSS\&quot;)&quot;;&apos; &gt; out',
        ],
        [
            'test'   => '<IMG SRC=" &#14;  javascript:alert(\'XSS\');">',
            'expect' => '',
        ],
        [
            'test'   => '<SCRIPT/XSS SRC="http://ha.ckers.org/xss.js"></SCRIPT>',
            'expect' => '',
        ],
        [
            'test'   => '<BODY onload!#$%&()*~+-_.,:;?@[/|\]^`=alert("XSS")>',
            'expect' => '',
        ],
        [
            'test'   => '<SCRIPT/SRC="http://ha.ckers.org/xss.js"></SCRIPT>',
            'expect' => '',
        ],
        [
            'test'   => '<<SCRIPT>alert("XSS");//<</SCRIPT>',
            'expect' => 'alert(&quot;XSS&quot;);//',
        ],
        [
            'test'   => '<SCRIPT SRC=http://ha.ckers.org/xss.js?<B>',
            'expect' => '',
        ],
        [
            'test'   => '<SCRIPT SRC=//ha.ckers.org/.j>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC="javascript:alert(\'XSS\')"',
            'expect' => '',
        ],
        [
            'test'   => '<iframe src=http://ha.ckers.org/scriptlet.html <',
            'expect' => '',
        ],
        [
            'test'   => '<SCRIPT>a=/XSS/
alert(a.source)</SCRIPT>',
            'expect' => 'a=/XSS/
alert(a.source)',
        ],
        [
            'test'   => '\\";alert(\'XSS\');//',
            'expect' => '\&quot;;alert(&apos;XSS&apos;);//',
        ],
        [
            'test'   => '</TITLE><SCRIPT>alert("XSS");</SCRIPT>',
            'expect' => 'alert(&quot;XSS&quot;);',
        ],
        [
            'test'   => '<INPUT TYPE="IMAGE" SRC="javascript:alert(\'XSS\');">',
            'expect' => '',
        ],
        [
            'test'   => '<BODY BACKGROUND="javascript:alert(\'XSS\')">',
            'expect' => '',
        ],
        [
            'test'   => '<BODY ONLOAD=alert(\'XSS\')>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG DYNSRC="javascript:alert(\'XSS\')">',
            'expect' => '',
        ],
        [
            'test'   => '<IMG LOWSRC="javascript:alert(\'XSS\')">',
            'expect' => '',
        ],
        [
            'test'   => '<BGSOUND SRC="javascript:alert(\'XSS\');">',
            'expect' => '',
        ],
        [
            'test'   => '<BR SIZE="&{alert(\'XSS\')}">',
            'expect' => '',
        ],
        [
            'test'   => '<LAYER SRC="http://ha.ckers.org/scriptlet.html"></LAYER>',
            'expect' => '',
        ],
        [
            'test'   => '<LINK REL="stylesheet" HREF="javascript:alert(\'XSS\');">',
            'expect' => '',
        ],
        [
            'test'   => '<LINK REL="stylesheet" HREF="http://ha.ckers.org/xss.css">',
            'expect' => '',
        ],
        [
            'test'   => '<STYLE>@import\'http://ha.ckers.org/xss.css\';</STYLE>',
            'expect' => '@import&apos;http://ha.ckers.org/xss.css&apos;;',
        ],
        [
            'test'   => '<META HTTP-EQUIV="Link" Content="<http://ha.ckers.org/xss.css>; REL=stylesheet">',
            'expect' => '; REL=stylesheet&quot;&gt;',
        ],
        [
            'test'   => '<STYLE>BODY{-moz-binding:url("http://ha.ckers.org/xssmoz.xml#xss")}</STYLE>',
            'expect' => 'BODY{-moz-binding:url(&quot;http://ha.ckers.org/xssmoz.xml#xss&quot;)}',
        ],
        [
            'test'   => '<XSS STYLE="behavior: url(xss.htc);">',
            'expect' => '',
        ],
        [
            'test'   => '<STYLE>li {list-style-image: url("javascript:alert(\'XSS\')");}</STYLE><UL><LI>XSS',
            'expect' => 'li {list-style-image: url(&quot;javascript:alert(&apos;XSS&apos;)&quot;);}&lt;LI&gt;XSS',
        ],
        [
            'test'   => '<IMG SRC=\'vbscript:msgbox("XSS")\'>',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC="mocha:[code]">',
            'expect' => '',
        ],
        [
            'test'   => '<IMG SRC="livescript:[code]">',
            'expect' => '',
        ],
        [
            'test'   => '¼script¾alert(¢XSS¢)¼/script¾',
            'expect' => '¼script¾alert(¢XSS¢)¼/script¾',
        ],
        [
            'test'   => '<META HTTP-EQUIV="refresh" CONTENT="0;url=javascript:alert(\'XSS\');">',
            'expect' => '',
        ],
        [
            'test'   => '<META HTTP-EQUIV="refresh" CONTENT="0;url=data:text/html;base64,PHNjcmlwdD5hbGVydCgnWFNTJyk8L3NjcmlwdD4K">',
            'expect' => '',
        ],
        [
            'test'   => '<META HTTP-EQUIV="refresh" CONTENT="0; URL=http://;URL=javascript:alert(\'XSS\');">',
            'expect' => '',
        ],
        [
            'test'   => '<IFRAME SRC="javascript:alert(\'XSS\');"></IFRAME>',
            'expect' => '',
        ],
        [
            'test'   => '<FRAMESET><FRAME SRC="javascript:alert(\'XSS\');"></FRAMESET>',
            'expect' => '',
        ],
        [
            'test'   => '<TABLE BACKGROUND="javascript:alert(\'XSS\')">',
            'expect' => '',
        ],
        [
            'test'   => '<TABLE><TD BACKGROUND="javascript:alert(\'XSS\')">',
            'expect' => '',
        ],
        [
            'test'   => '<DIV STYLE="background-image: url(javascript:alert(\'XSS\'))">',
            'expect' => '',
        ],
        [
            'test'   => '<DIV STYLE="background-image:\\0075\\0072\\006C\\0028\'\\006a\\0061\\0076\\0061\\0073\\0063\\0072\\0069\\0070\\0074\\003a\\0061\\006c\\0065\\0072\\0074\\0028.1027\\0058.1053\\0053\\0027\\0029\'\\0029">',
            'expect' => '',
        ],
        [
            'test'   => '<DIV STYLE="background-image: url(&#1;javascript:alert(\'XSS\'))">',
            'expect' => '',
        ],
        [
            'test'   => '<DIV STYLE="width: expression(alert(\'XSS\'));">',
            'expect' => '',
        ],
        [
            'test'   => '<STYLE>@im\\port\'\\ja\\vasc\\ript:alert("XSS")\';</STYLE>',
            'expect' => '@im\port&apos;\ja\vasc\ript:alert(&quot;XSS&quot;)&apos;;',
        ],
        [
            'test'   => '<IMG STYLE="xss:expr/*XSS*/ession(alert(\'XSS\'))">',
            'expect' => '',
        ],
        [
            'test'   => '<XSS STYLE="xss:expression(alert(\'XSS\'))">',
            'expect' => '',
        ],
        [
            'test'   => 'exp/*<A STYLE=\'no\\xss:noxss("*//*");
xss:&#101;x&#x2F;*XSS*//*/*/pression(alert("XSS"))\'>',
            'expect' => 'exp/*',
        ],
        [
            'test'   => '<STYLE TYPE="text/javascript">alert(\'XSS\');</STYLE>',
            'expect' => 'alert(&apos;XSS&apos;);',
        ],
        [
            'test'   => '<STYLE>.XSS{background-image:url("javascript:alert(\'XSS\')");}</STYLE><A CLASS=XSS></A>',
            'expect' => '.XSS{background-image:url(&quot;javascript:alert(&apos;XSS&apos;)&quot;);}',
        ],
        [
            'test'   => '<STYLE type="text/css">BODY{background:url("javascript:alert(\'XSS\')")}</STYLE>',
            'expect' => 'BODY{background:url(&quot;javascript:alert(&apos;XSS&apos;)&quot;)}',
        ],
        [
            'test'   => '<!--[if gte IE 4]>
<SCRIPT>alert(\'XSS\');</SCRIPT>
<![endif]-->',
            'expect' => '
alert(&apos;XSS&apos;);
',
        ],
        [
            'test'   => '<BASE HREF="javascript:alert(\'XSS\');//">',
            'expect' => '',
        ],
        [
            'test'   => '<OBJECT TYPE="text/x-scriptlet" DATA="http://ha.ckers.org/scriptlet.html"></OBJECT>',
            'expect' => '',
        ],
        [
            'test'   => '<OBJECT classid=clsid:ae24fdae-03c6-11d1-8b76-0080c744f389><param name=url value=javascript:alert(\'XSS\')></OBJECT>',
            'expect' => '',
        ],
        [
            'test'   => '<EMBED SRC="http://ha.ckers.org/xss.swf" AllowScriptAccess="always"></EMBED>',
            'expect' => '',
        ],
        [
            'test'   => '<EMBED SRC="data:image/svg+xml;base64,PHN2ZyB4bWxuczpzdmc9Imh0dH A6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcv MjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hs aW5rIiB2ZXJzaW9uPSIxLjAiIHg9IjAiIHk9IjAiIHdpZHRoPSIxOTQiIGhlaWdodD0iMjAw IiBpZD0ieHNzIj48c2NyaXB0IHR5cGU9InRleHQvZWNtYXNjcmlwdCI+YWxlcnQoIlh TUyIpOzwvc2NyaXB0Pjwvc3ZnPg==" type="image/svg+xml" AllowScriptAccess="always"></EMBED>',
            'expect' => '',
        ],
        [
            'test'   => '<HTML xmlns:xss>
  <?import namespace="xss" implementation="http://ha.ckers.org/xss.htc">
  <xss:xss>XSS</xss:xss>
</HTML>',
            'expect' => '
  
  XSS
',
        ],
        [
            'test'   => '<XML ID=I><X><C><![CDATA[<IMG SRC="javas]]><![CDATA[cript:alert(\'XSS\');">]]>
</C></X></xml><SPAN DATASRC=#I DATAFLD=C DATAFORMATAS=HTML></SPAN>',
            'expect' => ']]&gt;
',
        ],
        [
            'test'   => '<XML ID="xss"><I><B>&lt;IMG SRC="javas<!-- -->cript:alert(\'XSS\')"&gt;</B></I></XML>
<SPAN DATASRC="#xss" DATAFLD="B" DATAFORMATAS="HTML"></SPAN>',
            'expect' => '<I><B>&lt;IMG SRC="javascript:alert(\'XSS\')"&gt;</B></I>
',//xxx
        ],
        [
            'test'   => '<XML SRC="xsstest.xml" ID=I></XML>
<SPAN DATASRC=#I DATAFLD=C DATAFORMATAS=HTML></SPAN>',
            'expect' => '
',
        ],
        [
            'test'   => '<HTML><BODY>
<?xml:namespace prefix="t" ns="urn:schemas-microsoft-com:time">
<?import namespace="t" implementation="#default#time2">
<t:set attributeName="innerHTML" to="XSS&lt;SCRIPT DEFER&gt;alert(&quot;XSS&quot;)&lt;/SCRIPT&gt;">
</BODY></HTML>',
            'expect' => '



',
        ],
        [
            'test'   => '<SCRIPT SRC="http://ha.ckers.org/xss.jpg"></SCRIPT>',
            'expect' => '',
        ],
        [
            'test'   => '<!--#exec cmd="/bin/echo \'<SCR\'"--><!--#exec cmd="/bin/echo \'IPT SRC=http://ha.ckers.org/xss.js></SCRIPT>\'"-->',
            'expect' => '&apos;&quot;--&gt;',
        ],
        [
            'test'   => '<? echo(\'<SCR)\';
echo(\'IPT>alert("XSS")</SCRIPT>\'); ?>',
            'expect' => 'alert(&quot;XSS&quot;)&apos;); ?&gt;',
        ],
        [
            'test'   => '<IMG SRC="http://www.thesiteyouareon.com/somecommand.php?somevariables=maliciouscode">',
            'expect' => '<IMG SRC="http://www.thesiteyouareon.com/somecommand.php?somevariables=maliciouscode">',//xxx
        ],
        [
            'test'   => '<META HTTP-EQUIV="Set-Cookie" Content="USERID=&lt;SCRIPT&gt;alert(\'XSS\')&lt;/SCRIPT&gt;">',
            'expect' => '',
        ],
        [
            'test'   => '<HEAD><META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=UTF-7"> </HEAD>+ADw-SCRIPT+AD4-alert(\'XSS\');+ADw-/SCRIPT+AD4-',
            'expect' => ' +ADw-SCRIPT+AD4-alert(&apos;XSS&apos;);+ADw-/SCRIPT+AD4-',
        ],
        [
            'test'   => '<SCRIPT a=">" SRC="http://ha.ckers.org/xss.js"></SCRIPT>',
            'expect' => '&quot; SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;',
        ],
        [
            'test'   => '<SCRIPT =">" SRC="http://ha.ckers.org/xss.js"></SCRIPT>',
            'expect' => '&quot; SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;',
        ],
        [
            'test'   => '<SCRIPT a=">" \'\' SRC="http://ha.ckers.org/xss.js"></SCRIPT>',
            'expect' => '&quot; &apos;&apos; SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;',
        ],
        [
            'test'   => '<SCRIPT "a=\'>\'" SRC="http://ha.ckers.org/xss.js"></SCRIPT>',
            'expect' => '&apos;&quot; SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;',
        ],
        [
            'test'   => '<SCRIPT a=`>` SRC="http://ha.ckers.org/xss.js"></SCRIPT>',
            'expect' => '` SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;',
        ],
        [
            'test'   => '<SCRIPT a=">\'>" SRC="http://ha.ckers.org/xss.js"></SCRIPT>',
            'expect' => '&apos;&gt;&quot; SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;',
        ],
        [
            'test'   => '<SCRIPT>document.write("<SCRI");</SCRIPT>PT SRC="http://ha.ckers.org/xss.js"></SCRIPT>',
            'expect' => 'document.write(&quot;PT SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;',
        ],
        [
            'test'   => '<A HREF="javascript:document.location=\'http://www.google.com/\'">XSS</A>',
            'expect' => 'XSS',
        ],
    ];

    protected $basic_tags = [
        '<b>bold</b>', 
        '<blockquote>blockquote</blockquote>', 
        '<code>code</code>', 
        '<del>del</del>', 
        '<dd>dd</dd>', 
        '<dl>dl</dl>', 
        '<dt>dt</dt>', 
        '<em>em</em>', 
        '<h1>h1</h1>', 
        '<h2>h2</h2>', 
        '<h3>h3</h3>', 
        '<i>i</i>', 
        '<kbd>kbd</kbd>', 
        '<li>li</li>', 
        '<ol>ol</ol>', 
        '<p>p</p>', 
        '<pre>pre</pre>', 
        '<s>s</s>', 
        '<sup>sup</sup>', 
        '<sub>sub</sub>', 
        '<strong>strong</strong>', 
        '<strike>strike</strike>', 
        '<ul>ul</ul>',
        '<br>',
        '<hr>',
        '<br />',
        '<hr />',
    ];
    
    protected $valid_a = [
        '<a href="http://google.com">google</a>',
        '<a href="http://google.com" title="google">google</a>',
        '<a href="http://47giraffes.com">47Giraffes</a>',
        '<a href="http://47giraffes.com" title="47Giraffes" >47Giraffes</a>',
        '<a href="https://google.com">google</a>',
        '<a href="https://google.com" title="google">google</a>',
        '<a href="https://47giraffes.com">47Giraffes</a>',
        '<a href="https://47giraffes.com" title="47Giraffes" >47Giraffes</a>',
        '<a href="ftp://google.com">google</a>',
        '<a href="ftp://google.com" title="google">google</a>',
        '<a href="ftp://47giraffes.com">47Giraffes</a>',
        '<a href="ftp://47giraffes.com" title="47Giraffes" >47Giraffes</a>',
    ];
    
    protected $invalid_a = [
        '<a  href="http://google.com">google</a>'                             => 'google',
        '<a href=\'http://google.com\'>google</a>'                             => 'google',
        '<a href="http://google.com"  title="google">google</a>'              => 'google',
        '<a href="http://47giraffes.com">47Giraffes</a >'                     => '47Giraffes',
        '<a href="http://47giraffes.com" title="47Giraffes"  >47Giraffes</a>' => '47Giraffes',
        '<a href="tcp://google.com">google</a>'                               => 'google',
    ];

    /*/^(<img\ssrc="(https?:\/\/|\/)[-A-Za-z0-9+&@#\/%?=~_|!:,.;\(\)]+"(\swidth="\d{1,3}")?
        (\sheight="\d{1,3}")?(\salt="[^"<>]*")?(\stitle="[^"<>]*")?\s?\/?>)$/i';*/

    protected $valid_img = [
        '<img src="http://example.com/logo.png">',
        '<img src="http://example.com/logo.png"/>',
        '<img src="http://example.com/logo.png" />',
        '<img src="http://example.com/logo.png" width="111">',
        '<img src="http://example.com/logo.png" height="222">',
        '<img src="http://example.com/logo.png" alt="qwerty">',
        '<img src="http://example.com/logo.png" title="logo">',
    ];

    protected function setUp()
    {
        parent::setUp();
        $this->sanitize = new Sanitize;
    }

    public function testVaildBasicTags()
    {
        foreach ($this->basic_tags as $tag) {
            $this->assertEquals($tag, $this->sanitize->sanitize($tag));
        }
    }

    public function testVaildATags()
    {
        foreach ($this->valid_a as $tag) {
            $this->assertEquals($tag, $this->sanitize->sanitize($tag));
        }
    }

    public function testInvaildATags()
    {
        foreach ($this->invalid_a as $tag => $expect) {
            $this->assertEquals($expect, $this->sanitize->sanitize($tag));
        }
    }

    public function testVaildImageTag()
    {
        foreach ($this->valid_img as $tag) {
            $this->assertEquals($tag, $this->sanitize->sanitize($tag));
        }
    }

    public function testXss()
    {
        foreach ($this->xss as $xss) {
            $this->assertEquals($xss['expect'], $this->sanitize->sanitize($xss['test']));
        }

        $this->fail('All XSS test are assumed to have failed. '.
                    'The XSS tests are incomplete and existing tests require review');
    }
}
