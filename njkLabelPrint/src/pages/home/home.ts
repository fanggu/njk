import { Component, ViewChild, ElementRef } from '@angular/core';
import { NavController } from 'ionic-angular';
import JsBarcode from 'jsbarcode';
import * as moment from 'moment';
import { Cin7apiProvider } from '../../providers/cin7api/cin7api';

import { PriceLabelComponent } from "../../components/price-label/price-label"


@Component({
  selector: 'page-home',
  templateUrl: 'home.html'
})
export class HomePage {
  @ViewChild('barcode') barcode: ElementRef;

  public fromDate: string = '';
  public products: any;
  public labels: any[] = [];

  constructor(
    public navCtrl: NavController,
    private api: Cin7apiProvider
  ) {
    this.fromDate = moment().format();
    console.log('fromDate: ', moment().format());
    let _params = {
      'where' :`modifieddate>'${moment().utc().toISOString()}'`,
      'order' :'modifieddate ASC',
      'page' : 1,
      'rows' : 250
    };
    this.getProducts(_params);
  }

  dateSelected(){
    console.log('dateSelected: ', this.fromDate);
    let _params = {
      'where' :`modifieddate>'${moment.parseZone(this.fromDate).utc().toISOString()}'`,
      'order' :'modifieddate ASC',
      'page' : 1,
      'rows' : 250
    };
    this.getProducts(_params);
  }

  getProducts(params){
    if(params){
      console.log('getProducts->if->params: ', params);
      this.products =[{name:'Loading...'}];
      this.api.queryProducts(params)
      .subscribe(products => {
        this.products = products
        console.log(products);

        this.products.forEach((product) => {
          let po = product.productOptions[0];
          let label = {
            id: product.id,
            styleCode: product.styleCode,
            name: product.name,
            enName: po.option1,
            barcode: po.barcode,
            retailPrice: po.retailPrice.toFixed(2),
            specialPrice: po.specialDays === 0 ? 0 : po.specialPrice.toFixed(2)
          }

          this.labels.push(label);
        });

        console.log(this.labels);

      });
    }
  }

}
