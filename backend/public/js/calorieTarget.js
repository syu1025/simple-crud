document.addEventListener("DOMContentLoaded", function () {
    //設定ボタンを取得
    const submitButton = document.querySelector('button[form="targetCaloriesForm"]');
    //クリックイベントを追加
    submitButton.addEventListener("click", function (event){
        event.preventDefault(); // フォームのデフォルトの送信を防ぐ

        //input値を取得
        const targetCalories = document.getElementById("targetCaloriesBurned").value;
        //数値として扱う
        const targetCaloriesNumber = Number(targetCalories);

        //コンソールに表示
        console.log("設定された目標カロリー:", targetCaloriesNumber);
    });
});
