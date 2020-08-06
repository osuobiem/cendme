class Helper {
  static Map<String, String> states = {
    "9": "Abia",
    "17": "Abuja (FCT)",
    "20": "Adamawa",
    "10": "Akwa Ibom",
    "13": "Anambra",
    "22": "Bauchi",
    "34": "Bayelsa",
    "28": "Benue",
    "37": "Borno",
    "12": "Cross River",
    "26": "Delta",
    "36": "Ebonyi",
    "4": "Edo",
    "35": "Ekiti",
    "33": "Enugu",
    "19": "Gombe",
    "14": "Imo",
    "1": "Jigawa",
    "11": "Kaduna",
    "32": "Kano",
    "18": "Katsina",
    "31": "Kebbi",
    "29": "Kogi",
    "7": "Kwara",
    "6": "Lagos",
    "24": "Nasarawa",
    "8": "Niger",
    "23": "Ogun",
    "3": "Ondo",
    "2": "Osun",
    "15": "Oyo",
    "16": "Plateau",
    "5": "Rivers",
    "25": "Sokoto",
    "27": "Taraba",
    "30": "Yobe",
    "21": "Zamfara",
    "239": "No area with id 239",
  };

  static String getState(String areaId) {
    String state;
    states.forEach((key, value) {
      if (key == areaId) {
        state = value;
      }
    });
    return state;
  }

  static String getSvg(String name) {
    String svgName;
    if (name.toLowerCase().contains("plastic")) {
      svgName = "plastic.svg";
    }
    if (name.toLowerCase().contains("food")) {
      svgName = "food.svg";
    }
    if (name.toLowerCase().contains("drink")) {
      svgName = "drink.svg";
    }
    if (name.toLowerCase().contains("household")) {
      svgName = "household.svg";
    }
    if (name.toLowerCase().contains("miscellaneous")) {
      svgName = "miscellaneous.svg";
    }
    if (name.toLowerCase().contains("health")) {
      svgName = "health.svg";
    }
    if (name.toLowerCase().contains("baby")) {
      svgName = "baby.svg";
    }
    return svgName;
  }
}
