import { Route, Routes } from "react-router-dom";

import Home from "./pages/Home/Home.js";
import Login from "./pages/Login/Login.js";
import Signin from "./pages/Signin/Signin.js";
import Gare from "./pages/Gare/Gare.js";
import Iscrizioni from "./pages/Iscrizioni/Iscrizioni.js";
import GareCreate from "./pages/GareCreate/GareCreate.js";

export default function App() {
  return (
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/login" element={<Login />} />
      <Route path="/signin" element={<Signin />} />
      <Route path="/gare" element={<Gare />} />
      <Route path="/iscrizioni" element={<Iscrizioni />} />
      <Route path="/gareCreate" element={<GareCreate />} />
    </Routes>
  );
}
