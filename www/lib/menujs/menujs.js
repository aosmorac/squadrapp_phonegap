/*
 * MenuSlider 1.0
 *
 * Necsoiu Aristide
 * Copyright 2012, Licensed GPL & MIT
 *
*/
window.Menu = function (object) {

	var body = object,
		fingerCount = 0,
		startX = 0,
		startY = 0,
		curX = 0,
		curY = 0,
		deltaX = 0,
		deltaY = 0,
		horzDiff = 0,
		vertDiff = 0,
		minLength = 72, // the shortest distance the user may swipe
		swipeLength = 0,
		swipeAngle = null,
		swipeDirection = null,
		allowButton = false;

	//touch events delegation
	if (body.addEventListener) {
	    body.addEventListener('touchstart', handleEvent, false);
	    body.addEventListener('touchmove', handleEvent, false);
	    body.addEventListener('touchend', handleEvent, false);
	    body.addEventListener('touchcancel', handleEvent, false);
	}

	function handleEvent(e) {
	    switch (e.type) {
		case 'touchstart': onTouchStart(e); break;
	    case 'touchmove': onTouchMove(e); break;
	    case 'touchend': onTouchEnd(e); break;
	    case 'touchcancel': onTouchCancel(e); break;
	    }
	}

	function onTouchStart(e) {
		this.start = {
			// get touch coordinates for delta calculations in onTouchMove
			pageX: e.touches[0].pageX,
			pageY: e.touches[0].pageY,
			// set initial timestamp of touch sequence
			time: Number(new Date())
	    };

		// used for testing first onTouchMove event
		this.isScrolling = undefined;

		// reset deltaX
		this.deltaX = 0;

		// get the total number of fingers touching the screen
		fingerCount = e.touches.length;

		// since we're looking for a swipe (single finger) and not a gesture (multiple fingers),
		// check that only one finger was used
		if (fingerCount === 1) {
			// get the coordinates of the touch
			startX = e.touches[0].pageX;
			startY = e.touches[0].pageY;
			// store the triggering element ID
		} else {
			// more than one finger touched so cancel
			onTouchCancel(e);
		}
	}

	function onTouchMove(e) {

		//calculates the current distante swipped
		this.deltaX = e.touches[0].pageX - this.start.pageX;

		//as long and the property was created in the touchStart function
		if (typeof this.isScrolling === 'undefined') {
			this.isScrolling = !!(this.isScrolling || Math.abs(this.deltaX) < Math.abs(e.touches[0].pageY - this.start.pageY));
	    }

	    //test if the user is trying to scroll on the page and get current values
	    if (!this.isScrolling) {
			e.preventDefault();
			if (e.touches.length === 1) {
				curX = e.touches[0].pageX;
				curY = e.touches[0].pageY;
			} else {
				onTouchCancel(e);
			}
		}
	}

	function onTouchEnd(e) {
		// check to see if more than one finger was used and that there is an ending coordinate
		if (fingerCount === 1 && curX !== 0) {

			// use the Distance Formula to determine the length of the swipe
			swipeLength = Math.round(Math.sqrt(Math.pow(curX - startX, 2) + Math.pow(curY - startY, 2)));

			// if the user swiped more than the minimum length, perform the appropriate action
			if (swipeLength >= minLength) {

				caluculateAngle();
				determineSwipeDirection();
				processingRoutine();
				onTouchCancel(event); // reset the variables
			} else {
				onTouchCancel(event);
			}
		} else {
			onTouchCancel(event);
		}
	}

	function onTouchCancel(e) {
		// reset the variables back to default values
		fingerCount = 0;
		startX = 0;
		startY = 0;
		curX = 0;
		curY = 0;
		deltaX = 0;
		deltaY = 0;
		horzDiff = 0;
		vertDiff = 0;
		swipeLength = 0;
		swipeAngle = null;
		swipeDirection = null;
	}

	function caluculateAngle() {
		var X = startX - curX,
			Y = curY - startY,
			Z = Math.round(Math.sqrt(Math.pow(X, 2) + Math.pow(Y, 2))), //the distance - rounded - in pixels
			r = Math.atan2(Y, X); //angle in radians (Cartesian system)

		swipeAngle = Math.round(r * 180 / Math.PI); //angle in degrees

		if (swipeAngle < 0) {
			swipeAngle =  360 - Math.abs(swipeAngle);
		}
	}

	function determineSwipeDirection() {
		if ((swipeAngle <= 45) && (swipeAngle >= 0)) {
			swipeDirection = 'left';
		} else if ((swipeAngle <= 360) && (swipeAngle >= 315)) {
			swipeDirection = 'left';
		} else if ((swipeAngle >= 135) && (swipeAngle <= 225)) {
			swipeDirection = 'right';
		}
	}

	//associate function executin on specific swipe left or right
	function processingRoutine() {
		if (swipeDirection === 'left') {
			hideMenu();
		} else if (swipeDirection === 'right') {
			showMenu();
		}
	}

	//opening and clossing functions
	function showMenu(e) {
		body.className = "opening";
	}

	function hideMenu(e) {
        body.className = "closing";

        setTimeout(function() {
        	body.className = "";
		}, 200);
	}

	function quickHideMenu(e) {
        body.className = "wrapper1 qclosing";

        setTimeout(function() {
        	body.className = "";
		}, 200);
	}

	//functions to solve the window oveflow:hidden setting
	var ww = window.innerWidth,
		limit = 500,
		tOut;

	function reset() {
		ww = window.innerWidth;
		var w =  ww < limit ? (quickHideMenu()) :  (ww > limit ? (quickHideMenu()) : ww = limit);
	}

	function refresh() {
		ww = window.innerWidth;
		var w =  ww < limit ? (location.reload(true)) :  (ww > limit ? (location.reload(true)) : ww = limit);
	}

	window.onresize = function () {
		var resW = window.innerWidth;
		clearTimeout(tOut);
		tOut = setTimeout(reset, 20);
	};
};

