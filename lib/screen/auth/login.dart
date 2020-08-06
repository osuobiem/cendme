import 'package:cendme/Customer/provider/user_provider.dart';
import 'package:cendme/enum/app_state_enum.dart';
import 'package:cendme/utils/validations.dart';
import 'package:cendme/widget/buttonWidget.dart';
import 'package:cendme/widget/loadingDialog.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class Login extends StatefulWidget {
  @override
  _LoginState createState() => _LoginState();
}

class _LoginState extends State<Login> {
  bool _isSelected = false;
  bool visible = true;

  void _radio() {
    setState(() {
      _isSelected = !_isSelected;
    });
  }

  Widget radioButton(bool isSelected) => Container(
        width: 16.0,
        height: 16.0,
        padding: EdgeInsets.all(2.0),
        decoration: BoxDecoration(
          shape: BoxShape.circle,
          border: Border.all(width: 2.0, color: Theme.of(context).accentColor),
        ),
        child: isSelected
            ? Container(
                width: double.infinity,
                height: double.infinity,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: Theme.of(context).accentColor,
                ),
              )
            : Container(),
      );

  @override
  Widget build(BuildContext context) {
    UserProvider loginProvider = Provider.of<UserProvider>(context);
    return Container(
        width: double.infinity,
//        height: 400,
        child: Form(
          key: loginProvider.loginFormKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: <Widget>[
              Align(
                alignment: Alignment.center,
                child: Text('Login',
                    style: Theme.of(context)
                        .textTheme
                        .headline3
                        .merge(TextStyle(fontSize: 40, letterSpacing: 2))),
              ),
              SizedBox(
                height: 20,
              ),
              Text('Email',
                  style: Theme.of(context).textTheme.bodyText1.merge(TextStyle(
                        fontSize: 20,
                      ))),
              SizedBox(
                height: 10,
              ),
              TextFormField(
                validator: (value) => Validations.validateEmail(value, true),
                keyboardType: TextInputType.emailAddress,
                onSaved: (value) => loginProvider.user.email = value,
                decoration: InputDecoration(
                  contentPadding: EdgeInsets.only(left: 10),
                  hintText: 'email',
                  hintStyle: TextStyle(color: Colors.grey, fontSize: 15.0),
                  enabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.all(Radius.circular(10.0)),
                    borderSide:
                        BorderSide(color: Theme.of(context).accentColor),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.all(Radius.circular(10.0)),
                    borderSide:
                        BorderSide(color: Theme.of(context).accentColor),
                  ),
                ),
              ),
              SizedBox(
                height: 20,
              ),
              Text('Password',
                  style: Theme.of(context).textTheme.bodyText1.merge(TextStyle(
                        fontSize: 20,
                      ))),
              SizedBox(
                height: 10,
              ),
              TextFormField(
                keyboardType: TextInputType.visiblePassword,
                validator: (value) => Validations.validatePassword(value),
                onSaved: (value) => loginProvider.user.password = value,
                obscureText: visible ? true : false,
                decoration: InputDecoration(
                  contentPadding: EdgeInsets.only(left: 10),
                  hintText: '*******',
                  suffixIcon: IconButton(
                      onPressed: () {
                        visible = visible ? false : true;
                        setState(() {});
                      },
                      icon: visible
                          ? Icon(Icons.visibility_off,
                              color: Theme.of(context).accentColor)
                          : Icon(Icons.visibility,
                              color: Theme.of(context).accentColor)),
                  hintStyle: TextStyle(color: Colors.grey, fontSize: 12.0),
                  enabledBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.all(Radius.circular(10.0)),
                    borderSide:
                        BorderSide(color: Theme.of(context).accentColor),
                  ),
                  focusedBorder: OutlineInputBorder(
                    borderRadius: BorderRadius.all(Radius.circular(10.0)),
                    borderSide:
                        BorderSide(color: Theme.of(context).accentColor),
                  ),
                ),
              ),
              SizedBox(
                height: 30,
              ),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: <Widget>[
                  GestureDetector(
                    onTap: _radio,
                    child: Row(
                      children: <Widget>[
                        radioButton(_isSelected),
                        SizedBox(
                          width: 10,
                        ),
                        Text('Remember me',
                            style: Theme.of(context).textTheme.subtitle2.merge(
                                TextStyle(
                                    fontSize: 15,
                                    fontWeight: FontWeight.w500))),
                      ],
                    ),
                  ),
                  Text('Forgot Password?',
                      style: Theme.of(context).textTheme.subtitle1.merge(
                          TextStyle(fontSize: 15, fontWeight: FontWeight.w600)))
                ],
              ),
              SizedBox(
                height: 15,
              ),
              if (loginProvider.appState == AppState.loading)
                LoadingDialog(),
              ButtonWidget(
                onPressed: () {
                  if (loginProvider.loginFormKey.currentState.validate()) {
                    loginProvider.loginFormKey.currentState.save();

                    loginProvider.user.remember_me = _isSelected;
                    loginProvider.login(context);
                  }
                },
                text: 'Log In',
              ),
            ],
          ),
        ));
  }
}
