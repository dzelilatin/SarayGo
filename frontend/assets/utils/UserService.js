let UserService = {
  getUserId: function () {
    const userToken = localStorage.getItem("user_token");
    const decodedToken = jwt_decode(userToken);
    const userID = decodedToken.user.user_id;

    return userID;
  },
};
