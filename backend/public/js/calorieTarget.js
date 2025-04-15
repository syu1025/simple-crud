document.addEventListener("DOMContentLoaded", function () {

    //csrfトークンをaxiosのデフォルトヘッダーに設定
    axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    //設定ボタンを取得(idで)
    const submitButton = document.querySelector('button[form="targetCaloriesForm"]');

    //設定ボタンがクリックされたら
    submitButton.addEventListener("click", async(e) =>{
        e.preventDefault(); // フォームのデフォルトの送信を防ぐ

        //input値を取得(idで)
        const targetCalories = document.getElementById("targetCaloriesBurned").value;
        //数値として扱う
        const targetCaloriesNumber = Number(targetCalories);

        //コンソールに表示
        //console.log("設定された目標カロリー:", targetCaloriesNumber);

        try{
            //axiosでpost, 引数で送信先とデータ(キーと変数)を指定
            const response = await axios.post("/calorie-target/store", {
                target_burned_calories_day: targetCaloriesNumber
            });

            //console.log(response.data);

            //成功したらモーダルを閉じる
            document.getElementById('targeModal').close();
            //UIの更新も
            const display = document.getElementById("currentTargetValue");
            display.textContent = response.data.target_burned_calories_day;
        } catch (error) {
            //エラーがあったらコンソールに表示
            console.error(error.response?.data || error);
        }
    });
});
