import React from 'react';
import './App.css';
import { Switch, Route } from 'react-router-dom'
import 'bootstrap/dist/css/bootstrap.min.css';
import Home from "./pages/Home";
import CreateChat from "./pages/CreateChat";
import Chat from "./pages/Chat";

function App() {
  return (
    <div className="App">
      <Switch>
        <Route exact path='/' component={Home}/>
        <Route path='/new' component={CreateChat}/>
        <Route path='/chat/:code' component={Chat}/>
        {/*<Route path='/schedule' component={Schedule}/>*/}
      </Switch>
    </div>
  );
}

export default App;
