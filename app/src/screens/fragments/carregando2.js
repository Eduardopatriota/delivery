import React, { Component } from 'react';
import { View } from "react-native";
import {Text} from 'native-base';
import load from './../../../assets/logiad.json';
import Lottie from 'lottie-react-native';

// import { Container } from './styles';

export default class Carregando extends Component {
  render() {
    return (
      <View style={{ marginTop: '65%', backgroundColor: '#fff' }}>
        <View style={{ justifyContent: 'center', alignItems: 'center' }}>
          <Lottie resizeMode="contain" autoSize source={load} autoPlay loop style={{width: "30%", marginLeft: -5}} />
        </View>
        <View style={{ justifyContent: 'center', alignItems: 'center' }}>
          <Text style={{
            marginTop: 25,
            textAlign: 'center',
            fontSize: 18,
            height: 50,
            color: "#8f8e8e",
            fontWeight: "bold",

            width: '100%'
          }}
          >
            Carregando...
                </Text>
        </View>
      </View>
    );
  }
}
