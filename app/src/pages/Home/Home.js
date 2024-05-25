import "./Home.css";
import NavbarTop from "../../components/NavbarTop/NavbarTop.js";
import Container from "react-bootstrap/esm/Container.js";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from "react-bootstrap/Card";
import UserInfo from "../../components/UserInfo/UserInfo.js";
import GarePubblicate from "../../components/GarePubblicate/GarePubblicate.js";

export default function Home() {
  return (
    <div className="App">
      <NavbarTop />
      {localStorage.getItem("id_utente") != null ? (
        <Container className="">
          <Row>
            <Col sm={12} className="p-2">
              <UserInfo />
            </Col>
          </Row>
          <Row>
            <Col sm={12} className="p-2 ">
              <Card className="p-2 shadow-lg rounded">
                <GarePubblicate />
              </Card>
            </Col>
          </Row>
        </Container>
      ) : (
        <Card className="m-5 p-5">
          <h1 className="text-center">Accedi per visualizzare la dashboard</h1>
        </Card>
      )}
    </div>
  );
}
