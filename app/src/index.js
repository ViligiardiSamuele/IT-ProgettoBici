import React from "react";
import ReactDOM from "react-dom/client";
import reportWebVitals from "./reportWebVitals";
import { createBrowserRouter, RouterProvider } from 'react-router-dom';

//bootstrap css
import "bootstrap/dist/css/bootstrap.min.css";

//routes
import Notfound from "./pages/Notfound/Notfound.js";
import Home from "./pages/Home/Home.js";
import Login from "./pages/Login/Login.js";

//bootstrap dark mode
document.body.setAttribute("data-bs-theme", "dark");
const router = createBrowserRouter([
  {
    path: "/",
    element: <Home />,
    errorElement: <Notfound />,
  },
  /*
  {
      path: '/singup',
      element: <Singup />
  },*/
  {
      path: '/login',
      element: <Login />
  },
]);

const root = ReactDOM.createRoot(document.getElementById("root"));

root.render(
  <React.StrictMode>
    <RouterProvider router={router} />
  </React.StrictMode>
);

reportWebVitals();
