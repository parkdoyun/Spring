package com.example.pushtest;

import android.util.Log;
import android.widget.Toast;

import java.io.DataOutputStream;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Arrays;
import java.util.Scanner;

import com.google.api.client.googleapis.auth.oauth2.GoogleCredential;

import com.google.api.client.json.Json;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.google.gson.JsonObject;

public class Messaging {
    private static final String MESSAGING_SCOPE = "https://www.googleapis.com/auth/firebase.messaging";
    private static final String[] SCOPES = {MESSAGING_SCOPE};
    private static final String PROJECT_ID = "fcm-push-test-87943";
    private static final String BASE_URL = "https://fcm.googleapis.com";
    private static final String FCM_SEND_ENDPOINT = "/v1/projects/" + PROJECT_ID + "/messages:send";
    private static final String TITLE = "FCM Notification TITLE"; // 타이틀 제목
    private static final String BODY = "server message test"; // 메세지 내용
    public static final String MESSAGE_KEY = "message";


    private static String getAccessToken() throws IOException {
        // 웹서버에 올려놓은 json 파일 파싱해서 OAuth 인증키 생성
        URL JsonUrl = new URL("http://3.36.230.124/fcm_auth.json");
        HttpURLConnection con = (HttpURLConnection)JsonUrl.openConnection();
        InputStream is = con.getInputStream();

        GoogleCredential googleCredential = GoogleCredential
                .fromStream(is)
                .createScoped(Arrays.asList(SCOPES));
        googleCredential.refreshToken();

        return googleCredential.getAccessToken();
    }

    private static HttpURLConnection getConnection() throws IOException {
        // [START use_access_token]
        URL url = new URL(BASE_URL + FCM_SEND_ENDPOINT);
        HttpURLConnection httpURLConnection = (HttpURLConnection) url.openConnection();
        httpURLConnection.setRequestProperty("Authorization", "Bearer " + getAccessToken());
        httpURLConnection.setRequestProperty("Content-Type", "application/json; UTF-8");
        System.out.println("==================================");
        System.out.println(getAccessToken());
        System.out.println("==================================");
        return httpURLConnection;
        // [END use_access_token]
    }

    private static void sendMessage(JsonObject fcmMessage) throws IOException {
        HttpURLConnection connection = getConnection();
        connection.setDoOutput(true);
        DataOutputStream outputStream = new DataOutputStream(connection.getOutputStream());
        outputStream.writeBytes(fcmMessage.toString());
        outputStream.flush();
        outputStream.close();

        int responseCode = connection.getResponseCode();
        if (responseCode == 200) {
            String response = inputstreamToString(connection.getInputStream());
            System.out.println("Message sent to Firebase for delivery, response:");
            Log.d("SUCCESS", "Message sent to Firebase for delivery");
            System.out.println(response);
        } else {
            System.out.println("Unable to send message to Firebase:");
            String response = inputstreamToString(connection.getErrorStream());
            Log.d("FAILED", "Unable to send message to Firebase");
            System.out.println(response);
        }
    }

    private static String inputstreamToString(InputStream inputStream) throws IOException {
        StringBuilder stringBuilder = new StringBuilder();
        Scanner scanner = new Scanner(inputStream);
        while (scanner.hasNext()) {
            stringBuilder.append(scanner.nextLine());
        }
        return stringBuilder.toString();
    }

    private static JsonObject buildNotificationMessage(String token, String id, String name, String send_id, String send_yn) {
        // Json 내용
        JsonObject jNotification = new JsonObject();
        jNotification.addProperty("title", TITLE);
        jNotification.addProperty("body", BODY);

        JsonObject jData = new JsonObject();
        jData.addProperty("ID", id);
        jData.addProperty("NAME", name);
        jData.addProperty("SEND_ID", send_id);
        jData.addProperty("YN", send_yn);

        JsonObject jMessage = new JsonObject();
        jMessage.add("notification", jNotification);
        jMessage.add("data", jData);
        jMessage.addProperty("token", token);

        JsonObject jFcm = new JsonObject();
        jFcm.add(MESSAGE_KEY, jMessage);

        return jFcm;
    }

    private static void prettyPrint(JsonObject jsonObject) {
        Gson gson = new GsonBuilder().setPrettyPrinting().create();
        System.out.println(gson.toJson(jsonObject) + "\n");
    }

    public static void sendCommonMessage(String token, String id, String name, String send_id, String send_yn) throws IOException {
        JsonObject notificationMessage = buildNotificationMessage(token, id, name, send_id, send_yn);
        System.out.println("FCM request body for message using common notification object:");
        prettyPrint(notificationMessage);
        sendMessage(notificationMessage);
    }
}
