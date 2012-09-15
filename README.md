Aura.SanitizeHtml
-----------------

This library is **not** battle tested. Its output should not be trusted especially from users you don't know or trust.

Based on [Pagedown][1] for [StackOverflow][2] by [Jeff Atwood][3]


Warnings
========
   * Do not use user input that is included inside javascript or script tags.
   * Do not use user input that is included inside CSS.
   * The src value in an image is not checked i.e. `<img src="mywebsite.com/delete_something">`
     This is especially important if URLs can change state. (Your URLs shouldn't change state.)


[1]: http://code.google.com/p/pagedown
[2]: http://stackoverflow.com
[3]: http://www.codinghorror.com