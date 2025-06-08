let OrderService = {
  purchaseItems: function (data) {
    const userID = UserService.getUserId();
    const userToken = localStorage.getItem("user_token");
    console.log(userID);

    $.ajax({
      url: `http://saraygo.local/api/user/cart/deletecart/${userID}`,
      type: "DELETE",
      headers: {
        Authentication: userToken,
      },
      success: function (data) {
        console.log("Delete successful:", data);
      },
      error: function (error) {
        toastr.success("Successfuly purchased!");
        console.error("Delete failed:", error);
      },
    });
  },

  
};
