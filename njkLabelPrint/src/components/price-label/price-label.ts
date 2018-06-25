import { Component, Input, ViewChild, ElementRef } from '@angular/core';
import JsBarcode from 'jsbarcode';

@Component({
  selector: 'price-label',
  templateUrl: 'price-label.html'
})



export class PriceLabelComponent {

  @ViewChild('barcodeSvg') barcodeSvg: ElementRef;

  @Input() labelContent:any ;

  constructor() {
    console.log('Hello PriceLabelComponent Component');
    // JsBarcode(this.barcodeSvg.nativeElement, this.labelContent.barcode);
  }

  ngAfterViewInit() {
    console.log ('ionViewDidLoad', this.labelContent.barcode);
    let _barcode = this.labelContent.barcode === '' ? '0000000000000' : this.labelContent.barcode;
    let _format = _barcode.length === 13? 'EAN13' : 'CODE128';
    JsBarcode(
      this.barcodeSvg.nativeElement,
      _barcode,
      {
        format: _format,
        lineColor: "#000",
        fontSize: 12,
        width: 1.2,
        height: 30,
        displayValue: true,
        flat: true,
        textMargin: 0,
        margin: 1
      }
    );
  }

}
