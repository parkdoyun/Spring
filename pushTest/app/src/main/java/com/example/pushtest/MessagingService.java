package com.example.pushtest;

import static com.example.pushtest.Messaging.sendCommonMessage;

import android.app.AlertDialog;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Handler;
import android.support.v4.app.*;
import android.util.Log;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.core.app.NotificationCompat;

import com.google.api.client.googleapis.auth.oauth2.GoogleCredential;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.messaging.RemoteMessage;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.FileInputStream;
import java.io.IOException;
import java.util.Arrays;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;

import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;


public class MessagingService extends com.google.firebase.messaging.FirebaseMessagingService{
    private static final String TAG = "FirebaseMsgService";
    DatabaseReference mDB;
    String id, _id, _send_id, _send_yn;
    String _name;

    // msg 받으면 -> push 알림 띄우기
    @Override
    public void onMessageReceived(@NonNull RemoteMessage message) {

        _id = "";
        _name = "";
        Map<String, String> data = message.getData();
        _id = data.get("ID");
        _name = data.get("NAME");
        _send_id = data.get("SEND_ID");
        _send_yn = data.get("YN");

        sendNotification(message, _id, _name); // title, body 알림

    }

    // start refresh_token
    // token 서버 전송
    @Override
    public void onNewToken(@NonNull String token) {
        Log.d(TAG, "Refreshed token : " + token);

        // google firebase db 갱신 (fcmToken)
        mDB = FirebaseDatabase.getInstance().getReference();
        id =((MainActivity)MainActivity.context_main).editTextSignID.getText().toString();
        Map<String, Object>map = new HashMap<>();
        map.put("fcmToken", token);
        mDB.child("users").child(id).updateChildren(map);
    }

    // 푸시 띄우기
    private void sendNotification(@NonNull RemoteMessage messageBody, String _id, String _name)
    {

        Intent intent = new Intent(this, MainActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_SINGLE_TOP);

        // request code : 0
        PendingIntent pendingIntent = PendingIntent.getActivity(this, 0, intent, PendingIntent.FLAG_ONE_SHOT);

        // alarm sound
        Uri defaultSoundUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);

        // 내용 실어서 dialog로 띄우자
        Intent mainIntent = new Intent(this, DialogActivity.class);
        mainIntent.putExtra("ID", _id);
        mainIntent.putExtra("NAME", _name);
        mainIntent.putExtra("SEND_ID", _send_id);
        mainIntent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        PendingIntent DialogPendingIntent = PendingIntent.getActivity(this, 0, mainIntent, PendingIntent.FLAG_ONE_SHOT);

        // push 알림 설정
        // ContentText에 getBody()만 들어가네
        NotificationCompat.Builder notificationBuilder;
        if(_name.compareTo("end") != 0) // end 문자열이 아닌 경우 (다이얼로그)
        {
            notificationBuilder = new NotificationCompat.Builder(this)
                    .setSmallIcon(R.mipmap.ic_launcher)
                    .setContentTitle("예약 신청 알림")
                    .setContentText(" [" + _send_id + ", " + _name + "]")
                    .setAutoCancel(true)
                    .setSound(defaultSoundUri)
                    .setContentIntent(DialogPendingIntent);
        }
        else // end 문자열인 경우 -> 수락 허가 여부
        {
            notificationBuilder = new NotificationCompat.Builder(this)
                    .setSmallIcon(R.mipmap.ic_launcher)
                    .setContentTitle("수락 여부 알림")
                    .setContentText(" [" + _name + " : " + _send_id + " => " + _send_yn + "]")
                    .setAutoCancel(true)
                    .setSound(defaultSoundUri)
                    .setContentIntent(pendingIntent);
        }

        // 등록
        NotificationManager notificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        notificationManager.notify(0, notificationBuilder.build());

    }


}
