package com.example.pushtest;

import static com.example.pushtest.Messaging.sendCommonMessage;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Context;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.ValueEventListener;
import com.google.firebase.messaging.FirebaseMessaging;
import com.google.firebase.database.FirebaseDatabase;

import java.io.IOException;

// 0. 로그인하면서 firebase에 정보 등록 (사용자 ID, FCM 토큰 값 등록/있다면 수정, 없다면 등록)

// 1. 사용자 -> 점주에게 FCM 요청 메시지 전송 및 DB 예약 정보 추가 (점주 ID 이용해 토큰 찾아서 사용자 ID 같이 전송해야 함)
// (점주 ID, 사용자 ID, 매장 ID, 테이블 번호, 예약 시간) 전송 data
// 2. 점주가 해당 푸시 알림 클릭하여 수락/거절 유무 선택 (푸시 누르면 다이얼로그 뜸)
// 2-1. 수락 -> DB 수정(수락으로 상태 바꿈)
// 2-2. 거절 -> DB 수정(해당 예약 정보 삭제)
// 3. 클릭 후 자동으로 사용자에게 FCM 전송 (점주 ID, 사용자 ID, 매장 ID, 테이블 번호, 예약 시간, 수락 결과)

// 인앱 메시지와 알림 둘다 되도록 해보자
// 다른 사용자에게 알림 보내기 해보자 (폰 두개 가능한가?)

public class MainActivity extends AppCompatActivity {

    Button btnFcmSign, btnPost;
    String id = "";
    String send_id, send_name;
    String token; // 사용자 ID와 fcm 토큰
    DatabaseReference mDB; // firebase db handler
    int successChk, tokenChk;
    EditText editTextMsg, editTextName, editTextSignID;
    String tmpToken;
    TextView textViewInfo;

    public static Context context_main;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        btnFcmSign = (Button) findViewById(R.id.btnFcmSign);
        btnPost = (Button) findViewById(R.id.btnPost);
        editTextMsg = (EditText) findViewById(R.id.editTextMsg);
        editTextName = (EditText) findViewById(R.id.editTextName);
        editTextSignID = (EditText) findViewById(R.id.editTextSignID);
        textViewInfo = (TextView) findViewById(R.id.textViewInfo);

        mDB = FirebaseDatabase.getInstance().getReference();
        successChk = 0; // 1일 때 등록 성공한 거임
        tokenChk = 0;

        context_main = this; // 다른 액티비티에서 변수 쓰기 위해


        // "user"라는 토픽에 등록
        // FirebaseMessaging.getInstance().subscribeToTopic("user");

        // 현재 등록 토큰 가져오기
        FirebaseMessaging.getInstance().getToken().addOnCompleteListener(new OnCompleteListener<String>() {
            @Override
            public void onComplete(@NonNull Task<String> task) {
                if(!task.isSuccessful()){
                    Log.w("token failed", task.getException());
                    return;
                }

                // new FCM registration token
                token = task.getResult();
                Log.d("token", token);
                successChk = 1;
            }
        });

        // 버튼 누르면 정보 등록
        btnFcmSign.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                id = editTextSignID.getText().toString();
                if(successChk == 1 && id.length() != 0)
                {
                    // 기존 정보 삭제 후 등록(만약 있다면)

                    mDB.child("users").child(id).removeValue().addOnSuccessListener(new OnSuccessListener<Void>() {
                        @Override
                        public void onSuccess(Void unused) { // 삭제되면 등록
                            mDB.child("users").child(id).child("fcmToken").setValue(token);
                            successChk = 2; // 두번 등록 못 하게
                            Toast.makeText(getApplication(), "token registraion success", Toast.LENGTH_SHORT).show();
                            String tmp_s = textViewInfo.getText().toString();
                            textViewInfo.setText(tmp_s + id);
                        }
                    });

                }
                else if(id.length() == 0)
                {
                    Toast.makeText(getApplication(), "ID is EMPTY", Toast.LENGTH_SHORT).show();
                }
            }
        });

        // 메시지 전송
        btnPost.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                // db에서 토큰 찾기 (ID phpTest)

                // id, name 받아서 푸시 보내기
                send_id = editTextMsg.getText().toString();
                send_name = editTextName.getText().toString();

                // token 읽기 -> 읽어오는데 send 하기 전 delay 존재
                readToken(send_id);

                // thread 생성
                Thread th = new Thread(new Runnable() {
                    @Override
                    public void run() {
                        try {

                            // 스레드 안에 toast 쓰려면 핸들러 써야함
                            //Toast.makeText(getApplication(), "hello", Toast.LENGTH_SHORT).show();

                            sendCommonMessage(tmpToken, send_id, send_name, id, "no");

                        } catch (Exception e) {
                            e.printStackTrace();
                        }

                    }
                });
                th.setDaemon(true); // main thread 종료 시 같이 종료되도록 설정

                // delay 처리
                Handler h = new Handler();
                h.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        th.start(); // 이 스레드 종료해야 함
                    }
                }, 500);

            }


        });


    }

    public void readToken(String id) // token 읽기
    {
        // 이거 영구 리스너 -> db 값 변할 때만 다시 불러옴 [함수 안에 쓰자]
        mDB.child("users").child(id).child("fcmToken").addValueEventListener(new ValueEventListener() {
            @Override
            public void onDataChange(@NonNull DataSnapshot snapshot) {
                tmpToken = snapshot.getValue(String.class); // 전역변수
            }

            @Override
            public void onCancelled(@NonNull DatabaseError error) {
            }
        });

    }

}