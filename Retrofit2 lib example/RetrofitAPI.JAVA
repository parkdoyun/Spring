package com.example.myapplication;

import com.google.gson.JsonObject;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Query;
import retrofit2.http.FieldMap;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public interface RetrofitAPI{

    @FormUrlEncoded
    @POST("/checkExist.php") // 통신할 페이지 이름, 꼭 슬래쉬 넣어야함!!!!

    // PostData 타입으로 결과 return
    Call<PostData> postData(@Field("_ID") String id,
                                  @Field("_PWD") String pwd);

    @GET("checkExist.php")
    Call<PostData> pData(@Query("_ID") String id, @Query("_PWD") String pwd);


    @FormUrlEncoded
    @POST("chkDB.php")
    Call<ResponseBody> postDB(
            @FieldMap Map<String, String> map
    );



    @GET("testDB.php") // 이 함수 사용, "URL/testDB.php"와 통신, GET 형식
    Call<dbData> getData(@Query("post_id") String id); // 서버로 "post_id"라는 String 변수 id 보내고 콜백으로 dbData형 받는다 

}
