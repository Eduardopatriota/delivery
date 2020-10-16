import Splash from './src/screens/splash/splash';
import Login from './src/screens/login/login';
import Main from './src/screens/main/main';
import Cardapio from './src/screens/cardapio/cardapio';
import Produto from './src/screens/produto/produtto';
import Endereco from './src/screens/endereco/endereco';
import Inicial from './src/screens/inicial/inicial';
import CadEndereco from './src/screens/endereco/cadEndereco';
import Pedidos from './src/screens/pedidos/pedidos';
import { createAppContainer } from 'react-navigation';
import { createStackNavigator } from 'react-navigation-stack';


const Navegacao = createStackNavigator({
  Splash: {
    screen: Splash,
    navigationOptions: {
      headerStyle: { display: "none" },
      headerLeft: null
    },
  },

  Login: {
    screen: Login,
    navigationOptions: {
      headerStyle: { display: "none" },
      headerLeft: null
    },
  },

  Main: {
    screen: Main,
    navigationOptions: {
      headerStyle: { display: "none" },
      headerLeft: null
    },
  },

  Cardapio: {
    screen: Cardapio,
    navigationOptions: {
      headerStyle: { display: "none" },
      headerLeft: null
    },
  },

  Produto: {
    screen: Produto,
    navigationOptions: {
      headerStyle: { display: "none" },
      headerLeft: null
    },
  },

  Endereco: {
    screen: Endereco,
    navigationOptions: {
      headerStyle: { display: "none" },
      headerLeft: null
    },
  },

  CadEndereco: {
    screen: CadEndereco,
    navigationOptions: {
      headerStyle: { display: "none" },
      headerLeft: null
    },
  },

  Inicial: {
    screen: Inicial,
    navigationOptions: {
      headerStyle: { display: "none" },
      headerLeft: null
    },
  },

  Pedidos: {
    screen: Pedidos,
    navigationOptions: {
      headerStyle: { display: "none" },
      headerLeft: null
    },
  }
});

export default createAppContainer(Navegacao);