package com.example.myapplication;


import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;

import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import okhttp3.OkHttpClient;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;


public class MainActivity extends AppCompatActivity {
    Retrofit retrofit;
    EditText editID;
    Button btn;
    TextView infoTxt;
    RetrofitAPI api;
    Call<dbData> call;



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        btn = (Button) findViewById(R.id.btn);
        editID = (EditText) findViewById(R.id.editTextID);
        infoTxt = (TextView) findViewById(R.id.textViewDB);

        Gson gson = new GsonBuilder().setLenient().create();

        // 로그 보기 위해 (data flow log 추적)
        HttpLoggingInterceptor interceptor = new HttpLoggingInterceptor();
        interceptor.setLevel(HttpLoggingInterceptor.Level.BODY);

        OkHttpClient client = new OkHttpClient.Builder().addInterceptor(interceptor).build();

// retrofit 생성
        retrofit = new Retrofit.Builder()
                .baseUrl("http://3.36.230.124/")
                .client(client)
                .addConverterFactory(GsonConverterFactory.create(gson))
                .build();

        api = retrofit.create(RetrofitAPI.class); // api 생성 (핸들러라고 생각하면 됨), 이 api로 함수 호출하여 서버와 통신

// ID를 서버에 보내면 UserInfo 테이블에서 검색 후 서버가 관련 정보 (PWD, Busi_Yn 콜백 시 보내줌)
        btn.setOnClickListener(new View.OnClickListener() { // 버튼 클릭하면
            @Override
            public void onClick(View view) {

                String s_id = editID.getText().toString(); // EditText에 입력받은 id

                call = api.getData(s_id); // 파라미터 전달 (retrofitAPI.java의 getData 함수 사용)

                call.enqueue(new Callback<dbData>() { // 함수 호출하여 서버와 통신 (GET)
                    @Override
                    public void onResponse(Call<dbData> call, Response<dbData> response) {
                        if(response.isSuccessful()) // 응답 성공 시
                        {
                            dbData d = response.body(); // 서버가 콜백으로 보낸 data catch
                            Log.d("SUCCESS", d.toString()); // 데이터 로그 찍음 (확인용)
                            String db_s = " ";
                            db_s = db_s + d.get_PWD() + " " + d.get_Yn().toString(); // 서버로부터 받은 data String에 넣기

                            // id 관련 데이터 화면에 뿌림
                            infoTxt.setText(db_s);
                        }
                    }

                    @Override // error
                    public void onFailure(Call<dbData> call, Throwable t) {
                        t.printStackTrace();
                    }
                });
            }
        });


    }




}