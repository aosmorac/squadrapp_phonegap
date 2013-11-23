# Swipe Menu 
Swipe Menu brings content navigating on mobile web as easy and as natural as any local app. Swipe Menu aims to embody the natural menu feel you get on a smartphone with the YouTube, Facebook or Spotify menu navigation and all in a 3kb size script.

## Demo
### To see it in action just click here from a touch-enabled mobile device [menujs.boxbreakout.com](http://menujs.boxbreakout.com)

## Usage
To have the menu working in your website you just need to folow this example:

``` html
  <div id="menuWrapper">
    <div class="fix">
      <ul id="menu">
        <li><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </div>
  </div>
  <div id="bodyWrapper">
    <div class="fix">
      //Here comes the content of the website
    </div>
  </div>
```

Above is the initial required structure –  a series of elements wrapped in two containers. The location of the div with the class of fix allows individual scrolling for the menu as well as for the website.

``` js
new Menu(document.getElementById('bodyWrapper'));
```
Place this at the bottom of the page, externally, to verify the page is ready.


## Config Options

Swipe Menu does not require you to configure the JavaScript file, but if you need, you can modify the speed and the animations of the css3 classes.
But be careful. If the CSS is not correct or messed up to much, the animation will break.



## Requirements
Swipe requires a device that supports CSS transforms and works best with devices that support touch. Both of these are not required for the code to run since Swipe does not include any feature detection in the core code. This decision was made due to the fact that all mobile web development should already have some sort of feature detection built into the page. I recommend using a custom build of [Modernizr](http://modernizr.com), don't recreate the wheel.

Sample use with Modernizr:

``` js
if ( Modernizr.csstransforms ) {
  window.mySwipe = new Swipe(document.getElementById('slider'));
}
```


## Nothing is perfect 
Nothing in the world is perfect and this script makes no difference. More features can be implemented, bugs may arise, etc. Depending on the requests and the number of people who will use it as well as my time, I will try to update it as often as possible. If you have any questions, contact me on [Twitter](http://twitter.com/teddynecsoiu) or [GitHub](http://github.com/boxbreakout).

## Special Thanks 
In my training towards a JavaScript ninja and creating Menu Swipe, I encountered some tricky spots. A bigger Ninja gave me a hand. His name is [Brad Birdsall](http://twitter.com/bradbirdsall), creator of [Swipe JS](http://swipejs.com) and hi's a really swell guy :).


## License
Swipe Menu mobile is &copy; 2013 [Teddy Necsoiu](http://boxbreakout.com) and is licensed under the terms of GPL &amp; MIT licenses. 