import { NgModule, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { PriceLabelComponent } from './price-label/price-label';
import { IonicPageModule } from "ionic-angular";
@NgModule({
	declarations: [PriceLabelComponent],
	imports: [IonicPageModule.forChild(PriceLabelComponent)],
	exports: [PriceLabelComponent],
	schemas: [CUSTOM_ELEMENTS_SCHEMA]
})
export class ComponentsModule {}
