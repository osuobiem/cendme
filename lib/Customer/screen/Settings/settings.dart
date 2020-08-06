import 'package:cendme/Customer/provider/user_provider.dart';
import 'package:cendme/enum/app_state_enum.dart';
import 'package:cendme/enum/app_theme_enum.dart';
import 'package:cendme/utils/helpers.dart';
import 'package:cendme/utils/theme_manager.dart';
import 'package:cendme/utils/validations.dart';
import 'package:cendme/widget/buttonWidget.dart';
import 'package:cendme/widget/listTitle.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:flutter/widgets.dart';
import 'package:fluttericon/font_awesome5_icons.dart';
import 'package:provider/provider.dart';

class Settings extends StatefulWidget {
  @override
  _SettingsState createState() => _SettingsState();
}

GlobalKey<FormState> updateFormKey;

class _SettingsState extends State<Settings> {
  @override
  void initState() {
    updateFormKey = GlobalKey<FormState>();
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          "Settings",
          style: Theme.of(context).textTheme.headline1.merge(
              TextStyle(fontWeight: FontWeight.w600, letterSpacing: 1.5)),
        ),
        centerTitle: true,
        elevation: 0.0,
//        backgroundColor: Theme.of(context).scaffoldBackgroundColor,
      ),
      body: SingleChildScrollView(
          padding: EdgeInsets.all(20),
          physics: BouncingScrollPhysics(),
          child: Form(
              key: updateFormKey,
              child: Consumer<UserProvider>(
                builder: (context, userProvider, child) {
                  userProvider.nameController.text = userProvider.user.name;
                  userProvider.phoneController.text = userProvider.user.phone;
                  userProvider.addressController.text =
                      userProvider.user.address;

                  return Column(
                    crossAxisAlignment: CrossAxisAlignment.stretch,
                    children: <Widget>[
                      Align(
                        alignment: Alignment.centerRight,
                        child: IconButton(
                          onPressed: () {
                            Provider.of<ThemeManager>(context, listen: false)
                                .setTheme(Theme.of(context).brightness ==
                                        Brightness.light
                                    ? AppTheme.Dark
                                    : AppTheme.Light);
//                        Provider.of<UserSharedPreferences>(context, listen: false).destroyUserSharedPreferences(context);
                          },
                          icon: Icon(
                              Theme.of(context).brightness == Brightness.light
                                  ? FontAwesome5.sun
                                  : FontAwesome5.moon),
                          color:
                              Theme.of(context).brightness == Brightness.light
                                  ? Colors.orangeAccent
                                  : Colors.white70,
                        ),
                      ),
                      Text(
                        "Personal Data",
                        style: TextStyle(
                            fontSize: 17.0,
                            letterSpacing: 1.5,
                            fontWeight: FontWeight.w600),
                      ),
                      TextFormField(
                        controller: userProvider.nameController,
                        onSaved: (newValue) =>
                            userProvider.user.name = newValue,
                        validator: (value) => Validations.validateName(value),
                        decoration: InputDecoration(
                            labelText: 'Name', focusColor: Colors.blue),
                        obscureText: false,
                      ),
                      SizedBox(
                        height: 10,
                      ),
                      TextFormField(
                        controller: userProvider.phoneController,
                        onSaved: (newValue) =>
                            userProvider.user.phone = newValue,
                        validator: (value) =>
                            Validations.validateMobileNo(value),
                        decoration: InputDecoration(
                            labelText: 'Phone Number', focusColor: Colors.blue),
                        obscureText: false,
                      ),
                      SizedBox(
                        height: 10,
                      ),
                      Text("Gender"),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.start,
                        children: <Widget>[
                          Radio(
                            visualDensity: VisualDensity(horizontal: -2),
                            value: "Male",
                            groupValue: userProvider.user.gender,
                            onChanged: (value) {
                              userProvider.changeGender(value);
                            },
                          ),
                          Text(
                            'Male',
                          ),
                          Radio(
                            value: "Female",
                            groupValue: userProvider.user.gender,
                            visualDensity: VisualDensity(horizontal: -2),
                            onChanged: (value) {
                              userProvider.changeGender(value);
                            },
                          ),
                          Text(
                            'Female',
                          ),
                        ],
                      ),
                      userProvider.checkGenderError == true
                          ? Text(
                              "Please select gender",
                              style: TextStyle(color: Colors.red),
                            )
                          : Container(),
                      Text(
                        "Location Data",
                        style: TextStyle(
                            fontSize: 17.0,
                            letterSpacing: 1.5,
                            fontWeight: FontWeight.w600),
                      ),
                      TextFormField(
                        controller: userProvider.addressController,
                        onSaved: (newValue) =>
                            userProvider.user.address = newValue,
                        validator: (value) =>
                            Validations.validateText(value, "Address"),
                        decoration: InputDecoration(
                            labelText: 'Address', focusColor: Colors.blue),
                        obscureText: false,
                      ),
                      SizedBox(
                        height: 20,
                      ),
                      InkWell(
                          onTap: () {
//                          userProvider.getStates();
                            showDialog(
                                context: context,
                                builder: (context) {
                                  return AlertDialog(
                                      title: Text(
                                        "Select State",
                                        style: Theme.of(context)
                                            .textTheme
                                            .headline2
                                            .merge(
                                              TextStyle(letterSpacing: 1.5),
                                            ),
                                      ),
                                      content: Container(
                                        height: 250,
                                        child: Row(
                                          children: <Widget>[
                                            Expanded(
                                              child: ListView(
                                                shrinkWrap: true,
                                                children: Helper.states.entries
                                                    .map((e) {
                                                  return InkWell(
                                                    onTap: () {
                                                      Navigator.pop(context);
                                                      userProvider
                                                          .chooseState(e.key);
                                                      viewAreaList(e.key);
                                                    },
                                                    child: Column(
                                                      crossAxisAlignment:
                                                          CrossAxisAlignment
                                                              .stretch,
                                                      mainAxisAlignment:
                                                          MainAxisAlignment
                                                              .start,
                                                      children: <Widget>[
                                                        Padding(
                                                          padding:
                                                              const EdgeInsets
                                                                  .all(8.0),
                                                          child: Text(e.value),
                                                        ),
                                                        line(context),
                                                      ],
                                                    ),
                                                  );
                                                }).toList(),
                                              ),
                                            ),
                                            SizedBox(
                                              width: 10,
                                            ),
                                            Column(
                                              mainAxisAlignment:
                                                  MainAxisAlignment.center,
                                              children: <Widget>[
                                                Icon(
                                                  FontAwesome5.arrow_up,
                                                  color: Theme.of(context)
                                                      .accentColor,
                                                  size: 15.0,
                                                ),
                                                SizedBox(
                                                  height: 20,
                                                ),
                                                Icon(
                                                  FontAwesome5.arrow_down,
                                                  color: Theme.of(context)
                                                      .accentColor,
                                                  size: 15.0,
                                                ),
                                              ],
                                            )
                                          ],
                                        ),
                                      ));
                                });
                          },
                          child: ListTitle(
                              header: "Change your state",
                              title: userProvider.user?.area)),
                      userProvider.checkStateError == true
                          ? Text(
                              "Please select state",
                              style: TextStyle(color: Colors.red),
                            )
                          : Container(),
                      SizedBox(
                        height: 20,
                      ),
                      if (userProvider.appState == AppState.loading)
                        FlatButton(
                          child: CupertinoActivityIndicator(),
                          onPressed: () {},
                          color: Theme.of(context).accentColor,
                        ),
                      if (userProvider.appState != AppState.loading)
                        ButtonWidget(
                          onPressed: () {
                            if (updateFormKey.currentState
                                    .validate() &&
                                userProvider.checkGenderAndState()) {
                              updateFormKey.currentState.save();
                              userProvider.updateInfo(context);
                            }
                          },
                          text: "Update",
                        )
                    ],
                  );
                },
              ))),
    );
  }

  void viewAreaList(String stateId) {
    showDialog(
        context: context,
        builder: (context) {
          return StatefulBuilder(
            builder: (context, setState) {
              return AlertDialog(
                  title: Text(
                    "Select Area",
                    style: Theme.of(context).textTheme.headline2.merge(
                          TextStyle(letterSpacing: 1.5),
                        ),
                  ),
                  content: Container(
                    height: 250,
                    child: Consumer<UserProvider>(
                      builder: (context, value, child) {
                        return value.listOfArea.length == 0
                            ? Center(
                                child: CupertinoActivityIndicator(),
                              )
                            : Row(
                                children: <Widget>[
                                  Expanded(
                                    child: ListView(
                                      shrinkWrap: true,
                                      children: value.listOfArea.map((e) {
                                        return InkWell(
                                          onTap: () {
                                            Navigator.pop(context);
                                            value.chooseArea(e);
                                          },
                                          child: Column(
                                            crossAxisAlignment:
                                                CrossAxisAlignment.stretch,
                                            mainAxisAlignment:
                                                MainAxisAlignment.start,
                                            children: <Widget>[
                                              Padding(
                                                padding:
                                                    const EdgeInsets.all(8.0),
                                                child: Text(e['name']),
                                              ),
                                              line(context),
                                            ],
                                          ),
                                        );
                                      }).toList(),
                                    ),
                                  ),
                                  SizedBox(
                                    width: 10,
                                  ),
                                  Column(
                                    mainAxisAlignment: MainAxisAlignment.center,
                                    children: <Widget>[
                                      Icon(
                                        FontAwesome5.arrow_up,
                                        color: Theme.of(context).accentColor,
                                        size: 15.0,
                                      ),
                                      SizedBox(
                                        height: 20,
                                      ),
                                      Icon(
                                        FontAwesome5.arrow_down,
                                        color: Theme.of(context).accentColor,
                                        size: 15.0,
                                      ),
                                    ],
                                  )
                                ],
                              );
                      },
                    ),
                  ));
            },
          );
        });
  }
}
