
import { initializeApp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-auth.js";

const firebaseConfig = {
    apiKey: "AIzaSyDFU5koYC2HwPaYyhODkmdEuRql30dGrQY",
    authDomain: "registration-app-5ab5d.firebaseapp.com",
    projectId: "registration-app-5ab5d",
    storageBucket: "registration-app-5ab5d.firebasestorage.app",
    messagingSenderId: "621931132159",
    appId: "1:621931132159:web:ac9f75a2e20d1a6a2c88ca",
    measurementId: "G-C9S2FXZH3G"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
auth.languageCode = 'en';

const provider = new GoogleAuthProvider();

const continueGoogle = document.getElementById('continue-with-google');

continueGoogle.addEventListener("click", async () => {
    

    try {

        const result = await signInWithPopup(auth, provider);

        const credential = GoogleAuthProvider.credentialFromResult(result);
        const token = credential.accessToken;
        const user = result.user;

        const payload = {
            token: token,
            email: user.email,
            name: user.displayName,
            photo: user.photoURL,
            uid: user.uid
        };

        console.log("Sending Google data:", payload);

        const res = await fetch("../data/google-login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(payload)
        });

        const data = await res.json();

        console.log("Server response:", data);

        if (data.status === "success") {
            // store session if needed
            sessionStorage.setItem("googleToken", token);
            sessionStorage.setItem("userEmail", user.email);
            sessionStorage.setItem("userName", user.displayName);
            sessionStorage.setItem("userPhoto", user.photoURL);
            window.location.href = data.redirect;

        } else {
            alert(data.message);
        }

    } catch (error) {
        console.error(error);
        alert("Google Signin failed");
    }

});