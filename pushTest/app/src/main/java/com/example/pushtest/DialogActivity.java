package com.example.pushtest;

import static com.example.pushtest.Messaging.sendCommonMessage;

import androidx.appcompat.app.AppCompatActivity;

import android.app.Activity;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.view.Window;
import android.widget.Button;
import android.widget.TextView;

public class DialogActivity extends Activity {

    private String getId, getName, sendToken, get_send_id;
    TextView textViewID, textViewName;
    Button btnAccept, btnReject;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        requestWindowFeature(Window.FEATURE_NO_TITLE); // title바 사라짐
        setContentView(R.layout.activity_dialog);

        textViewID = (TextView) findViewById(R.id.textViewID);
        textViewName = (TextView) findViewById(R.id.textViewName);
        btnAccept = (Button) findViewById(R.id.btnAccept);
        btnReject = (Button) findViewById(R.id.btnReject);

        getId = getIntent().getStringExtra("ID");
        getName = getIntent().getStringExtra("NAME");
        get_send_id = getIntent().getStringExtra("SEND_ID");

        String s = textViewID.getText().toString();
        textViewID.setText(s + get_send_id);
        String s1 = textViewName.getText().toString();
        textViewName.setText(s1 + getName);

        // 거절 누르면 거절 보내고
        btnReject.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                ((MainActivity)MainActivity.context_main).readToken(get_send_id);
                // thread 생성
                Thread th = new Thread(new Runnable() {
                    @Override
                    public void run() {
                        try {

                            // 스레드 안에 toast 쓰려면 핸들러 써야함
                            //Toast.makeText(getApplication(), "hello", Toast.LENGTH_SHORT).show();

                            sendCommonMessage(((MainActivity)MainActivity.context_main).tmpToken, getId, "end", ((MainActivity)MainActivity.context_main).id, "REJECT");

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
                finish(); // 다이얼로그 액티비티 닫기
            }
        });

        // 허락 누르면 다시 상대에게 수락 보냄
        btnAccept.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                ((MainActivity)MainActivity.context_main).readToken(get_send_id);
                // thread 생성
                Thread th = new Thread(new Runnable() {
                    @Override
                    public void run() {
                        try {

                            // 스레드 안에 toast 쓰려면 핸들러 써야함
                            //Toast.makeText(getApplication(), "hello", Toast.LENGTH_SHORT).show();

                            sendCommonMessage(((MainActivity)MainActivity.context_main).tmpToken, getId, "end", ((MainActivity)MainActivity.context_main).id, "ACCEPT");

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
                finish();
            }
        });
    }
}