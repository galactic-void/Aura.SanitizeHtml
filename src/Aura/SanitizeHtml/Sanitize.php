<?php
/**
 * 
 * This file is part of the Aura project for PHP.
 * 
 * @license http://opensource.org/licenses/bsd-license.php BSD
 * 
 */
namespace Aura\SanitizeHtml;

/**
 * 
 * Port of Markdown HTML Sanitize by Jeff Atwood for stack overflow.
 *
 * @see  http://www.codinghorror.com/blog/2008/10/programming-is-hard-lets-go-shopping.html
 *
 * @see  http://refactormycode.com/codes/333-sanitize-html
 * 
 * @package Aura.SanitizeHtml
 * 
 */
class Sanitize
{
    /**
     * 
     * Basic tags without attributes regex.
     * 
     * @var string
     *
     */
    protected $basic_tag_whitelist = '/^(<\/?(b|blockquote|code|del|dd|dl|dt|em|h1|h2|h3|i|kbd|li|ol|p|pre|s|sup|sub|strong|strike|ul)>|<(br|hr)\s?\/?>)$/i';

    /**
     * 
     * Link regex.
     * 
     * @var string
     *
     */
    protected $a_white = '/^(<a\shref="((https?|ftp):\/\/|\/)[-A-Za-z0-9+&@#\/%?=~_|!:,.;\(\)]+"(\stitle="[^"<>]+")?\s?>|<\/a>)$/i';

    /**
     * 
     * Image regex.
     * 
     * @var string
     *
     */
    protected $img_white = '/^(<img\ssrc="(https?:\/\/|\/)[-A-Za-z0-9+&@#\/%?=~_|!:,.;\(\)]+"(\swidth="\d{1,3}")?(\sheight="\d{1,3}")?(\salt="[^"<>]*")?(\stitle="[^"<>]*")?\s?\/?>)$/i';


    /**
     * 
     * Sanitize HTML input against the white lists.
     * 
     * @param  string $input
     * 
     * @return string
     * 
     */
    public function sanitize($input)
    {
        $html = preg_replace_callback(
            '/<[^>]*>?/i',
            [$this, 'sanitizeTag'],
            $input
        );

        return $this->balanceTags($html);
    }

    /**
     * 
     * Sanitize a single tag against the white lists.
     *
     * If a tag is not in the white list or fails the white list check
     * return a empty string.
     * 
     * @param  string $tag
     * 
     * @return string Empty string if failed, else the white listed tag.
     * 
     */
    protected function sanitizeTag($tag)
    {
        if (preg_match($this->basic_tag_whitelist, $tag[0])) {

            return $tag[0];

        } elseif (preg_match($this->a_white, $tag[0])) {

            return $tag[0];

        } elseif (preg_match($this->img_white, $tag[0])) {

            return $tag[0];
        }

        return '';
    }

    /**
     * 
     * Attempt to balance HTML tags in the html string
     * by removing any unmatched opening or closing tags
     * IMPORTANT: we *assume* HTML has *already* been 
     * sanitized and is safe/sane before balancing.
     * 
     * @param  string $html
     * 
     * @return string 
     *
     */
    protected function balanceTags($html)
    {
        if ('' == $html) {
            return '';
        }

        $regex = '/<\/?\w+[^>]*(\s|$|>)/';

        $count = preg_match_all($regex, $html, $tags);
        $tags  = array_map('strtolower', $tags[0]);

        // no HTML tags present? nothing to do
        if (0 == $count) {
            return htmlspecialchars($html, \ENT_QUOTES|\ENT_HTML5, 'UTF-8', false);// todo
        }

        $ignore_tags   = ['<p>', '<img>', '<br>', '<li>', '<hr>'];
        $needs_removal = false;
        $removetag     = [];

        for ($ctag = 0; $ctag < $count; $ctag++) {
            $tagname = preg_replace('/<\/?(\w+).*/', '$1', $tags[$ctag]);

            // skip any already paired tags
            // and skip tags in our ignore list; assume they're self-closed
            if ((isset($tagpaired[$ctag]) && $tagpaired[$ctag]) ||
                in_array('<'.$tagname.'>', $ignore_tags)) {

                continue;
            }

            $tag = $tags[$ctag];
            $match = -1;

            if ('/' != $tag[1]) {
                // this is an opening tag
                // search forwards (next tags), look for closing tags
                for ($ntag = $ctag + 1; $ntag < $count; $ntag++) {

                    if ((! isset($tagpaired[$ntag]) || ! $tagpaired[$ntag]) &&
                        $tags[$ntag] == '</' . $tagname . '>') {

                        $match = $ntag;
                        break;
                    }
                }
            }

            if (-1 == $match) {
                $needs_removal = $removetag[$ctag] = true;
            } else {
                $tagpaired[$match] = true;
            }
        }

        if (! $needs_removal) {
            return $html;
        }

        $ctag   = 0;
        $remove = function ($match) use (&$ctag, $removetag) {
            $res = isset($removetag[$ctag]) && $removetag[$ctag] ? '' : $match[0];
            $ctag++;
            return $res;
        };
        $html  = preg_replace_callback($regex, $remove, $html);

        return htmlspecialchars($html, \ENT_QUOTES|\ENT_HTML5, 'UTF-8', false);// todo
    }
}

