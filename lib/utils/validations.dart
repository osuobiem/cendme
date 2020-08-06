class Validations {
  static String validateMobileNo(String val) {
    if (val.isEmpty) return "Phone Number is required";
    if (val.isNotEmpty && val.length < 11) return "Incorrect Mobile Number";
    if (!isNumeric(val)) return "Phone number should only contain digits!";
    return null;
  }

  static String validateName(String value) {
    if (value.isEmpty) return 'Name is Required.';
    final RegExp nameExp = new RegExp(r'^[A-za-z ]+$');
    if (!nameExp.hasMatch(value))
      return 'Please enter only alphabetical characters.';
    return null;
  }

  static String validateEmail(String value, [bool isRequried = true]) {
    if (value.isEmpty && isRequried) return 'Email is required.';
    final RegExp nameExp = new RegExp(
        r"^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,253}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,253}[a-zA-Z0-9])?)*$");
    if (!nameExp.hasMatch(value) && isRequried) return 'Invalid email address';
    return null;
  }

  static String validatePassword(String value) {
    if (value.isEmpty) return 'Password is Required.';
    return null;
  }

  static String validateRePassword(String value, String prevPass) {
    if (value.isEmpty) return 'Please re-enter your password.';
    if (prevPass != value) return 'Password does not match';
    return null;
  }

  static bool isNumeric(String s) {
    if (s == null) {
      return false;
    }
    return double.tryParse(s) != null;
  }

  static String validateText(String value,String fieldName) {
    if (value.isEmpty) return '$fieldName is Required.';
    return null;
  }
}
