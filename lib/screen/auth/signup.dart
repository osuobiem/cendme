import 'package:cendme/Customer/provider/user_provider.dart';
import 'package:cendme/enum/app_state_enum.dart';
import 'package:cendme/utils/validations.dart';
import 'package:cendme/widget/buttonWidget.dart';
import 'package:cendme/widget/loadingDialog.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

class SignUp extends StatefulWidget {
  @override
  _SignUpState createState() => _SignUpState();
}

class _SignUpState extends State<SignUp> {
  bool visible = true;
  @override
  Widget build(BuildContext context) {
    UserProvider signUpProvider = Provider.of<UserProvider>(context);
    return Container(
        width: double.infinity,
//        height: 550,
        child: Form(
          key: signUpProvider.signUpFormKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: <Widget>[
              Align(
                alignment: Alignment.center,
                child: Text('Sign Up',
                    style: Theme.of(context)
                        .textTheme
                        .headline3
                        .merge(TextStyle(fontSize: 40, letterSpacing: 2))),
              ),
              SizedBox(
                height: 20,
              ),
              Text('Name',
                  style: Theme.of(context).textTheme.bodyText1.merge(TextStyle(
                        fontSize: 20,
                      ))),
              SizedBox(
                height: 10,
              ),
              TextFormField(
                keyboardType: TextInputType.text,
                validator: (value) => Validations.validateName(value),
                controller: signUpProvider.nameController,
                onSaved: (newValue) => signUpProvider.user.name = newValue,
                decoration: InputDecoration(
                  contentPadding: EdgeInsets.only(left: 10),
                  hintText: 'name',
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
              Text('Email',
                  style: Theme.of(context).textTheme.bodyText1.merge(TextStyle(
                        fontSize: 20,
                      ))),
              SizedBox(
                height: 10,
              ),
              TextFormField(
                keyboardType: TextInputType.emailAddress,
                validator: (value) => Validations.validateEmail(value),
                onSaved: (newValue) => signUpProvider.user.email = newValue,
                controller: signUpProvider.emailController,
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
              Text('Phone Number',
                  style: Theme.of(context).textTheme.bodyText1.merge(TextStyle(
                        fontSize: 20,
                      ))),
              SizedBox(
                height: 10,
              ),
              TextFormField(
                keyboardType: TextInputType.number,
                validator: (value) => Validations.validateMobileNo(value),
                controller: signUpProvider.phoneController,
                onSaved: (newValue) => signUpProvider.user.phone = newValue,
                decoration: InputDecoration(
                  contentPadding: EdgeInsets.only(left: 10),
                  hintText: 'phone',
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
                controller: signUpProvider.passwordController,
                onSaved: (newValue) => signUpProvider.user.password = newValue,
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
                height: 20,
              ),
              Text('Confirm password',
                  style: Theme.of(context).textTheme.bodyText1.merge(TextStyle(
                        fontSize: 20,
                      ))),
              SizedBox(
                height: 10,
              ),
              TextFormField(
                  keyboardType: TextInputType.visiblePassword,
                  validator: (value) => Validations.validateRePassword(
                      value, signUpProvider.passwordController.text),
                  obscureText: true,
                  decoration: InputDecoration(
                    contentPadding: EdgeInsets.only(left: 10),
                    hintText: 'confirm password',
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
                  )),
              SizedBox(
                height: 15,
              ),
              if (signUpProvider.appState == AppState.loading) LoadingDialog(),
              ButtonWidget(
                onPressed: () {
                  if (signUpProvider.signUpFormKey.currentState.validate()) {
                    signUpProvider.signUpFormKey.currentState.save();

                    signUpProvider.signUp(context);
                  }
                },
                text: 'Sign Up',
              ),
            ],
          ),
        ));
  }
}
