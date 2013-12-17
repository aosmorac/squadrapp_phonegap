package com.app;

import android.content.Intent;
import android.os.Bundle;
import org.apache.cordova.*;
import org.apache.cordova.api.CordovaPlugin;

public class MainActivity extends DroidGap {

	@Override
    public void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        // Set by <content src="index.html" /> in config.xml
        
        super.setIntegerProperty("splashscreen", R.drawable.ic_launcher); // Displays the splash screen for android
        super.loadUrl("file:///android_asset/www/index.html",3000);
    }
	
    public void onActivityResult(int requestCode, int resultCode, Intent intent) {
        super.onActivityResult(requestCode, resultCode, intent);

        CordovaPlugin callback = this.activityResultCallback;
        if (callback != null) {
            callback.onActivityResult(requestCode, resultCode, intent);
        }
    }
    
}