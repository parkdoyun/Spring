package com.example.myapplication;

import com.google.gson.annotations.SerializedName;

// dbChk.php에서 파싱해올 것
// 데이터형
// 이 형태대로 서버와 주고 받는다고 생각하면 된다

public class dbData {
    @SerializedName("_ID") // php에서 보내는 변수명과 동일해야 함
    private String _ID;
    @SerializedName("_PWD")
    private String _PWD;
    @SerializedName("_Yn")
    private Boolean _Yn;

    public String get_ID() {
        return _ID;
    }

    public void set_ID(String _ID) {
        this._ID = _ID;
    }

    public String get_PWD() {
        return _PWD;
    }

    public void set_PWD(String _PWD) {
        this._PWD = _PWD;
    }

    public Boolean get_Yn() {
        return _Yn;
    }

    public void set_Yn(Boolean _Yn) {
        this._Yn = _Yn;
    }
}
